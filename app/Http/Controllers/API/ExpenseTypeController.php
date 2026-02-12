<?php

namespace App\Http\Controllers\API;

use App\ExpenseType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseTypeResource;
use App\Services\ExpenseTypeService;
use Exception;

class ExpenseTypeController extends Controller
{
    protected $expenseTypeService;

    public function __construct(ExpenseTypeService $expenseTypeService)
    {
        $this->expenseTypeService = $expenseTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expense_types = $this->expenseTypeService->paginateExpenseTypes();
        return ExpenseTypeResource::collection($expense_types);
    }

    public function all() {
        $expense_types = $this->expenseTypeService->getAllExpenseTypes();
        return ExpenseTypeResource::collection($expense_types);
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
            "name" => "required"
        ]);

        try {
            $expense_type = $this->expenseTypeService->createExpenseType($request->all());
            return (new ExpenseTypeResource($expense_type))->response()->setStatusCode(201);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ExpenseType  $expense_type
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseType $expense_type)
    {
        $expense_type = $this->expenseTypeService->findExpenseType($expense_type);
        return (new ExpenseTypeResource($expense_type));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ExpenseType  $expense_type
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ExpenseType $expense_type)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        try {
            $expense_type = $this->expenseTypeService->updateExpenseType($expense_type, $request->all());
            return (new ExpenseTypeResource($expense_type))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ExpenseType  $expense_type
     * @return \Illuminate\Http\Response
     */
    public function destroy(ExpenseType $expense_type)
    {
        try {
            $this->expenseTypeService->deleteExpenseType($expense_type);
            return (new ExpenseTypeResource($expense_type))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }
}
