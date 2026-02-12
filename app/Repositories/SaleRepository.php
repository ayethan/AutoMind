<?php

namespace App\Repositories;

use App\Car;
use App\Sale;
use App\Expense;
use App\Product;
use App\Service;
use App\Customer;
use App\SalePayment;
use App\SaleProduct;
use App\SaleService;
use App\Utils\Helpers;
use App\SaleExternalProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SaleRepository
{
    /**
     * Get all sales with optional filtering.
     *
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public function paginateSales(array $filters): LengthAwarePaginator
    {
        $q_builder = Sale::orderBy('id');

        if (isset($filters['status'])) {
            $q_builder->where('status', $filters['status']);
        }

        if (isset($filters['paid_status'])) {
            $q_builder->where('is_paid', filter_var($filters['paid_status'], FILTER_VALIDATE_BOOLEAN));
        }

        return $q_builder->paginate(config('tinyerp.default-pagination'));
    }

    /**
     * Create a new sale.
     *
     * @param array $data
     * @return Sale
     */
    public function createSale(array $data): Sale
    {
        return DB::transaction(function () use ($data) {
            $car = Car::firstOrCreate(['car_no' => strtoupper($data['car_no'])]);
            $sale = new Sale();
            $sale->car_id = $car->id;
            if ($car->customer_id) {
                $sale->customer_id = $car->customer_id;
            }
            $sale->fill($data);
            $sale->save();
            $sale->refresh();
            return $sale;
        });
    }

    /**
     * Find a sale by its model instance and load relations.
     *
     * @param Sale $sale
     * @return Sale
     */
    public function findSale(Sale $sale): Sale
    {
        return $sale->load('car', 'sale_products', 'sale_services', 'sale_external_products', 'expenses', 'payments', 'customer');
    }

    /**
     * Update an existing sale.
     *
     * @param Sale $sale
     * @param array $data
     * @return Sale
     */
    public function updateSale(Sale $sale, array $data): Sale
    {
        return DB::transaction(function () use ($sale, $data) {
            $sale->fill($data);
            $sale->save();

            if (isset($data['sale_products'])) {
                foreach ($data['sale_products'] as $sprod) {
                    $sale_product = $sale->sale_products()->find($sprod["id"]);
                    if ($sale_product) {
                        $sale_product->fill($sprod);
                        $sale_product->save();
                    }
                }
            }
            $sale->load('car', 'sale_products', 'sale_services', 'sale_external_products', 'expenses');
            return $sale;
        });
    }

    /**
     * Delete a sale.
     *
     * @param Sale $sale
     * @return bool|null
     */
    public function deleteSale(Sale $sale): ?bool
    {
        return DB::transaction(function () use ($sale) {
            $sale->expenses()->delete(); // Detaches and deletes expenses
            return $sale->delete();
        });
    }

    /**
     * Change the status of a sale.
     *
     * @param Sale $sale
     * @param int $status
     * @return Sale
     */
    public function changeSaleStatus(Sale $sale, int $status): Sale
    {
        $sale->status = $status;
        $sale->save();
        return $sale;
    }

    /**
     * Get sale status configuration.
     *
     * @return array
     */
    public function getSaleStatusConfig(): array
    {
        return config('tinyerp.sale-status');
    }

    /**
     * Add a product to a sale.
     *
     * @param Sale $sale
     * @param Product $product
     * @param int $qty
     * @return SaleProduct
     */
    public function addSaleProduct(Sale $sale, Product $product, int $qty): SaleProduct
    {
        return DB::transaction(function () use ($sale, $product, $qty) {
            $product->stock -= $qty;
            $product->save();

            $sold_product = $product->toArray();
            $sold_product["qty"] = $qty;
            $sold_product["product_id"] = $product->id;
            return $sale->sale_products()->create($sold_product);
        });
    }

    /**
     * Remove a product from a sale.
     *
     * @param SaleProduct $saleProduct
     * @return bool|null
     */
    public function removeSaleProduct(SaleProduct $saleProduct): ?bool
    {
        return DB::transaction(function () use ($saleProduct) {
            if ($saleProduct->product !== null) {
                $saleProduct->product->stock += $saleProduct->qty;
                $saleProduct->product->save();
            }
            return $saleProduct->delete();
        });
    }

    /**
     * Add a service to a sale.
     *
     * @param Sale $sale
     * @param Service $service
     * @return SaleService
     */
    public function addSaleService(Sale $sale, Service $service): SaleService
    {
        $sale_service_data = $service->toArray();
        $sale_service_data["service_id"] = $service->id;
        return $sale->sale_services()->create($sale_service_data);
    }

    /**
     * Remove a service from a sale.
     *
     * @param SaleService $saleService
     * @return bool|null
     */
    public function removeSaleService(SaleService $saleService): ?bool
    {
        return $saleService->delete();
    }

    /**
     * Get expenses related to a sale.
     *
     * @param Sale $sale
     * @return LengthAwarePaginator
     */
    public function getSaleExpenses(Sale $sale): LengthAwarePaginator
    {
        return $sale->expenses()->paginate(Helpers::getValue('default-pagination'));
    }

    /**
     * Add an expense to a sale.
     *
     * @param Sale $sale
     * @param array $data
     * @return Expense
     */
    public function addSaleExpense(Sale $sale, array $data): Expense
    {
        $expense = Expense::create($data);
        $sale->expenses()->attach($expense);
        return $expense;
    }

    /**
     * Remove an expense from a sale.
     *
     * @param Sale $sale
     * @param Expense $expense
     * @return bool
     */
    public function removeSaleExpense(Sale $sale, Expense $expense): bool
    {
        return DB::transaction(function () use ($sale, $expense) {
            $deleted = $expense->delete(); // Deletes the expense record
            $sale->expenses()->detach($expense->id); // Detaches it from the sale
            return $deleted;
        });
    }

    /**
     * Add an external product to a sale.
     *
     * @param Sale $sale
     * @param array $data
     * @return SaleExternalProduct
     */
    public function addSaleExternalProduct(Sale $sale, array $data): SaleExternalProduct
    {
        $external_product = new SaleExternalProduct();
        $external_product->fill($data);
        $external_product->qty = 1;
        $external_product->sale_id = $sale->id;
        $external_product->save();
        return $external_product;
    }

    /**
     * Remove an external product from a sale.
     *
     * @param SaleExternalProduct $externalProduct
     * @return bool|null
     */
    public function removeSaleExternalProduct(SaleExternalProduct $externalProduct): ?bool
    {
        return $externalProduct->delete();
    }

    /**
     * Change the customer for a sale.
     *
     * @param Sale $sale
     * @param Customer $customer
     * @return Sale
     */
    public function changeSaleCustomer(Sale $sale, Customer $customer): Sale
    {
        $sale->customer_id = $customer->id;
        $sale->save();
        return $sale;
    }

    /**
     * Remove the customer from a sale.
     *
     * @param Sale $sale
     * @return Sale
     */
    public function removeSaleCustomer(Sale $sale): Sale
    {
        $sale->customer_id = null;
        $sale->save();
        return $sale;
    }

    /**
     * Add a payment to a sale.
     *
     * @param Sale $sale
     * @param array $data
     * @return SalePayment
     */
    public function addSalePayment(Sale $sale, array $data): SalePayment
    {
        return $sale->payments()->create($data);
    }

    /**
     * Remove a payment from a sale.
     *
     * @param SalePayment $payment
     * @return bool|null
     */
    public function removeSalePayment(SalePayment $payment): ?bool
    {
        return $payment->delete();
    }

    /**
     * Mark a sale as paid.
     *
     * @param Sale $sale
     * @return Sale
     */
    public function makeSalePaid(Sale $sale): Sale
    {
        $sale->is_paid = true;
        $sale->save();
        return $sale;
    }

    /**
     * Mark a sale as closed (status = 2).
     *
     * @param Sale $sale
     * @return Sale
     */
    public function makeSaleClosed(Sale $sale): Sale
    {
        $sale->status = 2; // Assuming 2 is the closed status
        $sale->save();
        return $sale;
    }

    /**
     * Mark a sale as open (status = 1).
     *
     * @param Sale $sale
     * @return Sale
     */
    public function makeSaleOpen(Sale $sale): Sale
    {
        $sale->status = 1; // Assuming 1 is the open status
        $sale->save();
        return $sale;
    }

    /**
     * Retrieves sale data for invoice export.
     * This method is a helper to fetch data for the service layer to process.
     *
     * @param Sale $sale
     * @return Sale
     */
    public function getSaleForInvoice(Sale $sale): Sale
    {
        return $sale->load('car', 'sale_products', 'customer');
    }
}
