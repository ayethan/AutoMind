<?php

namespace App\Services;

use App\Sale;
use App\Product;
use App\Service;
use App\Customer;
use App\Expense;
use App\SaleProduct;
use App\SaleService as EloquentSaleService; // Alias to avoid conflict with service class
use App\SaleExternalProduct;
use App\SalePayment;
use App\Repositories\SaleRepository;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SaleService
{
    protected $saleRepository;
    protected $productRepository;

    public function __construct(SaleRepository $saleRepository, ProductRepository $productRepository)
    {
        $this->saleRepository = $saleRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * Get all sales with optional filtering.
     *
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginateSales(array $filters)
    {
        return $this->saleRepository->paginateSales($filters);
    }

    /**
     * Create a new sale.
     *
     * @param array $data
     * @return Sale
     */
    public function createSale(array $data)
    {
        return $this->saleRepository->createSale($data);
    }

    /**
     * Find a sale by its model instance and load relations.
     *
     * @param Sale $sale
     * @return Sale
     */
    public function findSale(Sale $sale)
    {
        return $this->saleRepository->findSale($sale);
    }

    /**
     * Update an existing sale.
     *
     * @param Sale $sale
     * @param array $data
     * @return Sale
     */
    public function updateSale(Sale $sale, array $data)
    {
        return $this->saleRepository->updateSale($sale, $data);
    }

    /**
     * Delete a sale.
     *
     * @param Sale $sale
     * @return bool|null
     */
    public function deleteSale(Sale $sale)
    {
        return $this->saleRepository->deleteSale($sale);
    }

    /**
     * Change the status of a sale.
     *
     * @param Sale $sale
     * @param int $status
     * @return Sale
     */
    public function changeSaleStatus(Sale $sale, int $status)
    {
        return $this->saleRepository->changeSaleStatus($sale, $status);
    }

    /**
     * Get sale status configuration.
     *
     * @return array
     */
    public function getSaleStatusConfig()
    {
        return $this->saleRepository->getSaleStatusConfig();
    }

    /**
     * Add a product to a sale.
     *
     * @param Sale $sale
     * @param int $productId
     * @param int $qty
     * @return SaleProduct
     * @throws Exception
     */
    public function addProduct(Sale $sale, int $productId, int $qty = 1)
    {
        $product = Product::findOrFail($productId); // Could use a ProductRepository here if one exists for findById

        if ($product->stock < $qty) {
            throw new Exception("{$product->name} insufficient stock.");
        }
        
        return $this->saleRepository->addSaleProduct($sale, $product, $qty);
    }

    /**
     * Remove a product from a sale.
     *
     * @param Sale $sale
     * @param SaleProduct $saleProduct
     * @return bool|null
     * @throws Exception
     */
    public function removeProduct(Sale $sale, SaleProduct $saleProduct)
    {
        if ($sale->id != $saleProduct->sale_id) {
            throw new Exception("Sale product does not belong to this sale.");
        }
        return $this->saleRepository->removeSaleProduct($saleProduct);
    }

    /**
     * Add a service to a sale.
     *
     * @param Sale $sale
     * @param int $serviceId
     * @return EloquentSaleService
     */
    public function addService(Sale $sale, int $serviceId)
    {
        $service = Service::findOrFail($serviceId); // Could use a ServiceRepository here if one exists
        return $this->saleRepository->addSaleService($sale, $service);
    }

    /**
     * Remove a service from a sale.
     *
     * @param Sale $sale
     * @param EloquentSaleService $saleService
     * @return bool|null
     * @throws Exception
     */
    public function removeService(Sale $sale, EloquentSaleService $saleService)
    {
        if ($sale->id != $saleService->sale_id) {
            throw new Exception("Sale service does not belong to this sale.");
        }
        return $this->saleRepository->removeSaleService($saleService);
    }

    /**
     * Get expenses related to a sale.
     *
     * @param Sale $sale
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getExpenses(Sale $sale)
    {
        return $this->saleRepository->getSaleExpenses($sale);
    }

    /**
     * Add an expense to a sale.
     *
     * @param Sale $sale
     * @param array $data
     * @return Expense
     */
    public function addExpense(Sale $sale, array $data)
    {
        return $this->saleRepository->addSaleExpense($sale, $data);
    }

    /**
     * Remove an expense from a sale.
     *
     * @param Sale $sale
     * @param Expense $expense
     * @return bool
     * @throws Exception
     */
    public function removeExpense(Sale $sale, Expense $expense): bool
    {
        $exists = DB::table('sale_expenses')->where('sale_id', $sale->id)->where('expense_id', $expense->id)->count() > 0;
        if(!$exists) {
            throw new Exception("Expense not attached to this sale.");
        }
        return $this->saleRepository->removeSaleExpense($sale, $expense);
    }

    /**
     * Add an external product to a sale.
     *
     * @param Sale $sale
     * @param array $data
     * @return SaleExternalProduct
     */
    public function addExternalProduct(Sale $sale, array $data)
    {
        return $this->saleRepository->addSaleExternalProduct($sale, $data);
    }

    /**
     * Remove an external product from a sale.
     *
     * @param Sale $sale
     * @param SaleExternalProduct $externalProduct
     * @return bool|null
     * @throws Exception
     */
    public function removeExternalProduct(Sale $sale, SaleExternalProduct $externalProduct)
    {
        if ($sale->id != $externalProduct->sale_id) {
            throw new Exception("External product does not belong to this sale.");
        }
        return $this->saleRepository->removeSaleExternalProduct($externalProduct);
    }

    /**
     * Change the customer for a sale.
     *
     * @param Sale $sale
     * @param int $customerId
     * @return Sale
     */
    public function changeCustomer(Sale $sale, int $customerId)
    {
        $customer = Customer::findOrFail($customerId); // Could use a CustomerRepository here if one exists
        return $this->saleRepository->changeSaleCustomer($sale, $customer);
    }

    /**
     * Remove the customer from a sale.
     *
     * @param Sale $sale
     * @return Sale
     */
    public function removeCustomer(Sale $sale)
    {
        return $this->saleRepository->removeSaleCustomer($sale);
    }

    /**
     * Add a payment to a sale.
     *
     * @param Sale $sale
     * @param array $data
     * @return SalePayment
     */
    public function addPayment(Sale $sale, array $data)
    {
        return $this->saleRepository->addSalePayment($sale, $data);
    }

    /**
     * Remove a payment from a sale.
     *
     * @param Sale $sale
     * @param SalePayment $payment
     * @return bool|null
     * @throws Exception
     */
    public function removePayment(Sale $sale, SalePayment $payment)
    {
        if ($payment->sale_id != $sale->id) {
            throw new Exception("Payment does not belong to this sale.");
        }
        return $this->saleRepository->removeSalePayment($payment);
    }

    /**
     * Mark a sale as paid.
     *
     * @param Sale $sale
     * @return Sale
     */
    public function makePaid(Sale $sale)
    {
        return $this->saleRepository->makeSalePaid($sale);
    }

    /**
     * Mark a sale as closed (status = 2).
     *
     * @param Sale $sale
     * @return Sale
     */
    public function makeClosed(Sale $sale)
    {
        return $this->saleRepository->makeSaleClosed($sale);
    }

    /**
     * Mark a sale as open (status = 1).
     *
     * @param Sale $sale
     * @return Sale
     */
    public function makeOpen(Sale $sale)
    {
        return $this->saleRepository->makeSaleOpen($sale);
    }

    /**
     * Export the sale invoice to an Excel file.
     *
     * @param Sale $sale
     * @return string The binary content of the Excel file.
     * @throws Exception
     */
    public function exportInvoice(Sale $sale)
    {
        $sale = $this->saleRepository->getSaleForInvoice($sale);
        
        $export_config = config('tinyerp.invoice-export');
        $template_path = public_path('InvoiceTemplate.xlsx');

        if (!file_exists($template_path)) {
            throw new Exception("Invoice template file not found at: {$template_path}");
        }

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $template = $reader->load($template_path);
        $worksheet = $template->getActiveSheet();

        $worksheet->getCell($export_config["cells"]["invoice-no"])->setValue("{$sale->id} ");
        $worksheet->getCell($export_config["cells"]["date"])->setValue(date('d-M-Y', strtotime($sale->date)));
        $worksheet->getCell($export_config["cells"]["car-no"])->setValue($sale->car->car_no);
        $worksheet->getCell($export_config["cells"]["job-done-by"])->setValue($sale->job_done_by);
        $worksheet->getCell($export_config["cells"]["customer"])->setValue($sale->customer->name ?? 'N/A');
        $worksheet->getCell($export_config["cells"]["model"])->setValue($sale->car->model);

        $worksheet->getCell($export_config["cells"]["subtotal"])->setValue($sale->sub_total);
        $worksheet->getCell($export_config["cells"]["tax"])->setValue($sale->tax);
        $worksheet->getCell($export_config["cells"]["discount"])->setValue($sale->discount);
        $worksheet->getCell($export_config["cells"]["grand-total"])->setValue($sale->total);

        $item_start_row = $export_config['item-row-range']['start'];
        $description_column = $export_config["item-columns"]["description"];
        $qty_column = $export_config["item-columns"]["qty"];
        $rate_column = $export_config["item-columns"]["rate"];
        $total_column = $export_config["item-columns"]["total"];

        foreach($sale->sale_products as $sprod) {
            $description = $sprod->remark ? "{$sprod->name} - {$sprod->remark}": $sprod->name;
            $worksheet->getCell("{$description_column}{$item_start_row}")->setValue($description);
            $worksheet->getCell("{$qty_column}{$item_start_row}")->setValue($sprod->qty);
            $worksheet->getCell("{$rate_column}{$item_start_row}")->setValue($sprod->sell_price);
            $worksheet->getCell("{$total_column}{$item_start_row}")->setValue($sprod->total);
            $item_start_row ++;
        }
        
        $filename = "invoice_{$sale->id}";
        
        ob_start();
        $writer = IOFactory::createWriter($template, 'Xlsx');
        $writer->save('php://output');
        return ob_get_clean();
    }
}
