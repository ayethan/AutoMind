<?php

namespace App\Http\Controllers\API;

use App\Sale;
use Exception;
use Validator;
use App\Expense;
use App\SaleProduct;
use App\SaleService as EloquentSaleService;
use App\SaleExternalProduct;
use App\SalePayment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Http\Resources\ExpenseResource;
use App\Http\Resources\SaleProductResource;
use App\Http\Resources\SaleServiceResource;
use App\Services\SaleService;
use Symfony\Component\HttpFoundation\StreamedResponse; // For file downloads

class SaleController extends Controller
{
    protected $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $filters = [
            'status' => request()->status,
            'paid_status' => request()->paid_status,
        ];

        $sales = $this->saleService->paginateSales($filters);

        return (SaleResource::collection($sales))->additional([
            'filter' => $filters,
            'status' => $this->saleService->getSaleStatusConfig()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'car_no' => 'required|string|max:255',
            'date' => 'required',
        ]);

        $sale = $this->saleService->createSale($request->all());

        return (new SaleResource($sale))->additional([
            'status' => $this->saleService->getSaleStatusConfig()
        ])->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function show(Sale $sale)
    {
        $sale = $this->saleService->findSale($sale);
        return new SaleResource($sale);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sale $sale)
    {
        $validator = Validator::make($request->all(), [
            'car_no' => 'required|string|max:255', // Changed from email to string
            'is_taxi'=> 'required',
            'date' => 'required',
            'discount' => 'numeric',
            'tax' => 'numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $sale = $this->saleService->updateSale($sale, $request->all());
            return new SaleResource($sale);
        } catch (Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Sale $sale
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sale $sale)
    {
        try {
            $this->saleService->deleteSale($sale);
            return new SaleResource($sale);
        } catch (Exception $e) {
            abort(500, 'Server Error');
        }
    }

    /**
     *
     * Change the stauts of the sale
     *
     */
    public function changeStatus(Sale $sale) {
        $sale = $this->saleService->changeSaleStatus($sale, request()->status);
        return ( new SaleResource($sale))->response()->setStatusCode(202);
    }

    /**
     * Get all available status of a sale
     */
    public function getStatus() {
        return $this->saleService->getSaleStatusConfig();
    }

    /**
     * Adds products to the sales
     *
     */
    public function addProduct(Request $request, Sale $sale) {
        try {
            $this->validate($request, [
                'product_id' => 'required|numeric',
                'qty' => 'required|numeric|min:1',
            ]);
            $saleProduct = $this->saleService->addProduct($sale, $request->product_id, $request->qty);
            $sale = $this->saleService->findSale($sale); // Reload sale with relations
            return (new SaleResource($sale))->response()->setStatusCode(201);
        } catch(Exception $e) {
            abort(500, $e->getMessage());
        }
    }

    /**
     * Removes the product from the sale
     *
     */
    public function removeProduct(Sale $sale, SaleProduct $sale_product) {
        try {
            $this->saleService->removeProduct($sale, $sale_product);
            $sale = $this->saleService->findSale($sale); // Reload sale with relations
            return (new SaleResource($sale))->response()->setStatusCode(202);
        } catch(Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Adds services to the sales
     *
     */
    public function addService(Request $request, Sale $sale) {
        try {
            $this->validate($request, [
                'service_id' => 'required|numeric',
            ]);
            $this->saleService->addService($sale, $request->service_id);
            $sale = $this->saleService->findSale($sale); // Reload sale with relations
            return (new SaleResource($sale))->response()->setStatusCode(201);
        } catch(Exception $e) {
            abort(500, "DB Error: " . $e->getMessage());
        }
    }

    /**
     * Removes the item from the sale
     *
     */
    public function removeService(Sale $sale, EloquentSaleService $sale_service) {
        try {
            $this->saleService->removeService($sale, $sale_service);
            $sale = $this->saleService->findSale($sale); // Reload sale with relations
            return (new SaleResource($sale))->response()->setStatusCode(202);
        } catch(Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /*
     * Expenses
     */
    public function getExpenses(Sale $sale) {
        $expenses = $this->saleService->getExpenses($sale);
        return ExpenseResource::collection($expenses);
    }

    public function addExpense(Request $request, Sale $sale) {
        $this->validate($request, [
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'expense_type_id' => 'required'
        ]);

        try {
            $this->saleService->addExpense($sale, $request->all());
            $sale = $this->saleService->findSale($sale); // Reload sale with relations
            return (new SaleResource($sale))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    public function removeExpense(Sale $sale, Expense $expense) {
        try {
            $this->saleService->removeExpense($sale, $expense);
            $sale = $this->saleService->findSale($sale); // Reload sale with relations
            return (new SaleResource($sale))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * External Products
     */
    public function addExternalProduct(Request $request, Sale $sale) {
        $this->validate($request, [
            'name' => 'required',
            'buy_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
        ]);

        try {
            $this->saleService->addExternalProduct($sale, $request->all());
            $sale = $this->saleService->findSale($sale); // Reload sale with relations
            return (new SaleResource($sale))->response()->setStatusCode(201);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    public function removeExternalProduct(Sale $sale, SaleExternalProduct $external_product) {
        try {
            $this->saleService->removeExternalProduct($sale, $external_product);
            return (new SaleResource($sale))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Customer
     */
    public function changeCustomer(Request $request, Sale $sale) {
        $this->validate($request, [
            'customer_id' => 'required|numeric'
        ]);

        try {
            $sale = $this->saleService->changeCustomer($sale, $request->customer_id);
            return (new SaleResource($sale));
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    public function removeCustomer(Sale $sale) {
        try {
            $sale = $this->saleService->removeCustomer($sale);
            return (new SaleResource($sale));
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Sale Payment
     */

     public function addPayment(Request $request, Sale $sale) {
        $this->validate($request, [
            "amount" => "required|numeric",
            "date" => "required"
        ]);

        try {
            $this->saleService->addPayment($sale, $request->only(['date', 'amount', 'remark']));
            $sale = $this->saleService->findSale($sale); // Reload sale with relations
            return (new SaleResource($sale));
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
     }

     public function removePayment(Sale $sale, SalePayment $payment) {
        try {
            $this->saleService->removePayment($sale, $payment);
            $sale = $this->saleService->findSale($sale); // Reload sale with relations
            return (new SaleResource($sale));
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
     }

    /**
     * Make Paid
     */

     public function makePaid(Sale $sale) {
        try {
            $sale = $this->saleService->makePaid($sale);
            return (new SaleResource($sale))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
     }

    /**
      * Make Closed
      */
     public function makeClosed(Sale $sale) {
        try {
            $sale = $this->saleService->makeClosed($sale);
            return (new SaleResource($sale))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
     }

     public function makeOpen(Sale $sale) {
        try {
            $sale = $this->saleService->makeOpen($sale);
            return (new SaleResource($sale))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
     }

    /**
     * Export The Sale Invoice
     */
    public function exportInvoice(Sale $sale) {
        try {
            $fileContent = $this->saleService->exportInvoice($sale);
            $filename = "invoice_{$sale->id}.xlsx";

            // Return a streamed response for file download
            return response()->streamDownload(function () use ($fileContent) {
                echo $fileContent;
            }, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                'Cache-Control' => 'max-age=0',
            ]);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }
}
