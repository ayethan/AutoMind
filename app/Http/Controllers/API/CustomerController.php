<?php

namespace App\Http\Controllers\API;

use App\Customer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use App\Services\CustomerService;
use Exception;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $customers = $this->customerService->paginateCustomers();
        return CustomerResource::collection($customers);
    }

    public function all() {
        $customers = $this->customerService->getAllCustomers();
        return CustomerResource::collection($customers);
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
            'name' => 'required',
            'phone' => 'required'
        ]);

        try {
            $customer = $this->customerService->createCustomer($request->all());
            return (new CustomerResource($customer))->response()->setStatusCode(201);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        $customer = $this->customerService->findCustomer($customer);
        return new CustomerResource($customer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        $this->validate($request, [
            'name' => 'required',
            'phone' => 'required'
        ]);

        try {
            $customer = $this->customerService->updateCustomer($customer, $request->all());
            return (new CustomerResource($customer))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try {
            $this->customerService->deleteCustomer($customer);
            return (new CustomerResource($customer))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }
}
