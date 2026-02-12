<?php

namespace App\Http\Controllers\API;

use App\Expense;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ExpenseResource;
use App\Services\ExpenseService;
use Exception;

class ExpenseController extends Controller
{
    protected $expenseService;

    public function __construct(ExpenseService $expenseService)
    {
        $this->expenseService = $expenseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = $this->expenseService->paginateExpenses();
        return ExpenseResource::collection($expenses);
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
            'amount' => 'required|numeric',
            'date' => 'required|date',
            'expense_type_id' => 'required|numeric'
        ]);

        try {
            $expense = $this->expenseService->createExpense($request->all());
            return (new ExpenseResource($expense))->response()->setStatusCode(201);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function show(Expense $expense)
    {
        $expense = $this->expenseService->findExpense($expense);
        return (new ExpenseResource($expense));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Expense $expense)
    {
        $this->validate($request, [
            'amount' => 'numeric',
            'date' => 'date',
            'expense_type_id' => 'numeric'
        ]);

        try {
            $expense = $this->expenseService->updateExpense($expense, $request->all());
            return (new ExpenseResource($expense))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Expense  $expense
     * @return \Illuminate\Http\Response
     */
    public function destroy(Expense $expense)
    {
        try {
            $this->expenseService->deleteExpense($expense);
            return (new ExpenseResource($expense))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }
}
