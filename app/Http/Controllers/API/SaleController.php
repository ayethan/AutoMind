<?php

namespace App\Http\Controllers\API;

use App\Car;
use App\Sale;
use Exception;
use Validator;
use App\Expense;
use App\Product;
use App\Service;
use App\Customer;
use App\SalePayment;
use App\SaleProduct;
use App\SaleService;
use App\Utils\Helpers;
use App\SaleExternalProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\SaleResource;
use App\Http\Resources\ExpenseResource;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Http\Resources\SaleProductResource;
use App\Http\Resources\SaleServiceResource;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $q_builder = Sale::orderBy('id');
        
        $filter_status = request()->status;
        $filter_paid_status = request()->paid_status;

        if($filter_status) {
            $q_builder->where('status', $filter_status);
        }

        if($filter_paid_status) {
            $q_builder->where('is_paid', filter_var($filter_paid_status, FILTER_VALIDATE_BOOLEAN));
        }

        $status = config('tinyerp.sale-status');
        
        $sales = $q_builder->paginate(config('tinyerp.default-pagination'));
        return (SaleResource::collection($sales))->additional([
            'filter' => [
                'status' => $filter_status,
                'paid_status' => $filter_paid_status
            ],
            'status' => $status
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

        //Step 1: Check if the car exists
        $car = Car::where('car_no', strtoupper($request->car_no))->first();

        if(empty($car)) {
            $car = Car::create(["car_no" => $request->car_no]);
        }

        $sale = new Sale();
        if($car->customer_id) {
            $sale->customer_id = $car->customer_id;
        }
        $sale->car_id = $car->id;
        $sale->fill($request->all());
        $sale->save();

        $sale->refresh();
        
        $status = config('tinyerp.sale-status');

        return (new SaleResource($sale))->additional([
            'status' => $status
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
        $sale->load('car', 'sale_products', 'sale_services', 'sale_external_products', 'expenses', 'payments', 'customer');
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
            'car_no' => 'required|string|email|max:255',
            'is_taxi'=> 'required',
            'date' => 'required',
            'discount' => 'numeric',
            'tax' => 'numeric',
        ]);

        try{
            DB::beginTransaction();
            $sale->fill($request->all());
            $sale->date = $request->date;
            $sale->status = $request->status;
            $sale->save();
            $sale->load('car', 'sale_products', 'sale_services', 'sale_external_products', 'expenses');
            
            foreach(request()->sale_products as $sprod) {
                $sale_product = $sale->sale_products()->find($sprod["id"]);
                $sale_product->fill($sprod);
                $sale_product->save();
            }
            
            DB::commit();
            $sale->refresh();
            return new SaleResource($sale);
        } catch(Exception $e) {
            DB::rollback();
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
            DB::beginTransaction();
            $sale->expenses()->delete();
            $sale->delete();
            DB::commit();
            return new SaleResource($sale);
        } catch(Exception $e) {
            DB::rollback();
            abort(500, 'Server Error');
        }
    }

    /**
     * 
     * Change the stauts of the sale
     * 
     */
    public function changeStatus(Sale $sale) {
        $sale->status = request()->status;
        $sale->save();
        return ( new SaleResource($sale))->response()->setStatusCode(202);
    }

    /**
     * Get all available status of a sale
     */
    public function getStatus() {
        return config('tinyerp.sale-status');
    }











    /**
     * 
     * Adds products to the sales
     * 
     */
    public function addProduct(Request $request, Sale $sale) {

        try {
            DB::beginTransaction();
                $product = Product::findOrFail($request->product_id);
                if($product->stock < 1) {
                    abort(500, "{$product->name} insufficient stock.");
                }
                $product->stock -= 1;
                $product->save();
                $sold_product = $product->toArray();
                $sold_product["qty"] = 1;
                $sold_product["product_id"] = $product->id;

                $sale->sale_products()->create($sold_product);
            DB::commit();
            $sale->load('car', 'sale_products', 'sale_services', 'sale_external_products', 'expenses');
            return (new SaleResource($sale))->response()->setStatusCode(201);
        } catch(Exception $e) {
            DB::rollback();
            abort(500, "DB Error");
        }
    }

    /**
     * 
     * Removes the product from the sale
     * 
     */
    public function removeProduct(Sale $sale, SaleProduct $sale_product) {

        if($sale->id != $sale_product->sale_id) {
            abort(400);
        }

        try {
            DB::beginTransaction();
            if($sale_product->product != null) {
                $sale_product->product->stock += $sale_product->qty;
                $sale_product->product->save();
            }
            $sale_product->delete();
            $sale->load('car', 'sale_services', 'sale_products', 'sale_external_products', 'expenses');
            DB::commit();
            return (new SaleResource($sale))->response()->setStatusCode(202);
        } catch(Exception $e) {
            DB::rollback();
            abort(500, 'Server Error');
        }
    }










    
    /**
     * 
     * Adds services to the sales
     * 
     */
    public function addService(Request $request, Sale $sale) {
        try {
            DB::beginTransaction();
                $service = Service::findOrFail($request->service_id);
                $sale_service = $service->toArray();
                $sale_service["service_id"] = $service->id;
                
                $sale->sale_services()->create($sale_service);
                
                DB::commit();
                $sale->load('car', 'sale_products', 'sale_services', 'sale_external_products', 'expenses');
            return (new SaleResource($sale))->response()->setStatusCode(201);
        } catch(Exception $e) {
            DB::rollback();
            abort(500, "DB Error");
        }
    }

    /**
     * 
     * Removes the item from the sale
     * 
     */
    public function removeService(Sale $sale, SaleService $sale_service) {
        if($sale->id != $sale_service->sale_id){
            abort(400);
        }

        $sale_service->delete();
        $sale->load('car', 'sale_services','sale_products', 'sale_external_products', 'expenses');
        return (new SaleResource($sale))->response()->setStatusCode(202);
    }











    /*
     * Expenses 
     */
    public function getExpenses(Sale $sale) {
        $expenses = $sale->expenses()->paginate(Helpers::getValue('default-pagination'));
        return ExpenseResource::collection($expenses);
    }

    public function addExpense(Request $request, Sale $sale) {
        $this->validate($request, [
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'expense_type_id' => 'required'
        ]);

        $expense = Expense::create($request->all());
        $sale->expenses()->attach($expense);
        $sale->load('car', 'sale_services','sale_products', 'sale_external_products', 'expenses');
        return (new SaleResource($sale))->response()->setStatusCode(202);
    }

    public function removeExpense(Sale $sale, Expense $expense) {
        $exists = DB::table('sale_expenses')->where('sale_id', $sale->id)->where('expense_id', $expense->id)->count() > 0;
        if($exists) {
            $expense->delete();
            $sale->load('car', 'sale_services','sale_products', 'sale_external_products', 'expenses');
            return (new SaleResource($sale))->response()->setStatusCode(202);
        }
        abort(404);
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

        $external_product = new SaleExternalProduct();
        $external_product->fill($request->all());
        $external_product->qty = 1;
        $external_product->sale_id = $sale->id;
        $external_product->save();

        $sale->load('car', 'sale_services','sale_products', 'sale_external_products', 'expenses');
        return (new SaleResource($sale))->response()->setStatusCode(201);
    }

    public function removeExternalProduct(Sale $sale, SaleExternalProduct $external_product) {
        if($sale->id != $external_product->sale_id) {
            abort(400);
        }
        $external_product->delete();
        return (new SaleResource($sale))->response()->setStatusCode(202);
    }











    /**
     * Customer 
     */
    public function changeCustomer(Request $request, Sale $sale) {
        $this->validate($request, [
            'customer_id' => 'required|numeric'
        ]);

        $customer = Customer::findOrFail($request->customer_id);

        $sale->customer_id = $customer->id;
        $sale->save();
        $sale->load('car', 'sale_products', 'sale_services', 'sale_external_products', 'expenses');

        return (new SaleResource($sale));
    }


    public function removeCustomer(Sale $sale) {
        $sale->customer_id = null;
        $sale->save();

        $sale->load('car', 'sale_products', 'sale_services', 'sale_external_products', 'expenses');
        return (new SaleResource($sale));
    }












    /**
     * Sale Payment
     */

     public function addPayment(Request $request, Sale $sale) {
        $this->validate($request, [
            "amount" => "required|numeric",
            "date" => "required"
        ]);

        $sale->payments()->create([
            "date" => $request->date,
            "amount" => $request->amount,
            "remark" => $request->remark
        ]);

        $sale->load('car', 'sale_products', 'sale_services', 'sale_external_products', 'expenses', 'payments');

        return (new SaleResource($sale));
     }

     public function removePayment(Sale $sale, SalePayment $payment) {
        if($payment->sale_id != $sale->id) {
            abort(400);
        }

        $payment->delete();    
        $sale->load('car', 'sale_products', 'sale_services', 'sale_external_products', 'expenses', 'payments');
        return (new SaleResource($sale));
     }











    /**
     * Make Paid
     */

     public function makePaid(Sale $sale) {
        $sale->is_paid = true;
        $sale->save();
        
        return (new SaleResource($sale))->response()->setStatusCode(202);
     }










     /**
      * Make Closed
      */
     public function makeClosed(Sale $sale) {
        $sale->status = 2;
        $sale->save();

        $sale->load('car', 'payments', 'customer');
        return (new SaleResource($sale))->response()->setStatusCode(202);
     }


     public function makeOpen(Sale $sale) {
        $sale->status = 1;
        $sale->save();

        $sale->load('car', 'payments', 'customer');
        return (new SaleResource($sale))->response()->setStatusCode(202);
     }









     
    /**
     * Export The Sale Invoice
     */
    public function exportInvoice(Sale $sale) {
        $sale->load('car', 'sale_products','customer');
        $export_config = config('tinyerp.invoice-export');
        $tempalte_path = public_path('InvoiceTemplate.xlsx');
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $template = $reader->load( $tempalte_path);

        $worksheet = $template->getActiveSheet();

        $worksheet->getCell($export_config["cells"]["invoice-no"])->setValue("{$sale->id} ");
        $worksheet->getCell($export_config["cells"]["date"])->setValue(date('d-M-Y', strtotime($sale->date)));
        $worksheet->getCell($export_config["cells"]["car-no"])->setValue($sale->car->car_no);
        $worksheet->getCell($export_config["cells"]["job-done-by"])->setValue($sale->job_done_by);
        $worksheet->getCell($export_config["cells"]["customer"])->setValue($sale->customer->name);
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

        $filename = $sale->id;

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment;filename={$filename}.xlsx");
        header('Cache-Control: max-age=0');
        $writer = IOFactory::createWriter($template, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

}
