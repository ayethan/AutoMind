<?php

namespace App\Services;

use App\Expense;
use App\Repositories\ExpenseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExpenseService
{
    protected $expenseRepository;

    public function __construct(ExpenseRepository $expenseRepository)
    {
        $this->expenseRepository = $expenseRepository;
    }

    /**
     * Get all expenses with pagination, ordered by ID descending.
     *
     * @return LengthAwarePaginator
     */
    public function paginateExpenses(): LengthAwarePaginator
    {
        return $this->expenseRepository->paginateExpenses();
    }

    /**
     * Create a new expense.
     *
     * @param array $data
     * @return Expense
     */
    public function createExpense(array $data): Expense
    {
        return $this->expenseRepository->createExpense($data);
    }

    /**
     * Find an expense by its model instance.
     *
     * @param Expense $expense
     * @return Expense
     */
    public function findExpense(Expense $expense): Expense
    {
        return $this->expenseRepository->findExpense($expense);
    }

    /**
     * Update an existing expense.
     *
     * @param Expense $expense
     * @param array $data
     * @return Expense
     */
    public function updateExpense(Expense $expense, array $data): Expense
    {
        return $this->expenseRepository->updateExpense($expense, $data);
    }

    /**
     * Delete an expense.
     *
     * @param Expense $expense
     * @return bool|null
     */
    public function deleteExpense(Expense $expense): ?bool
    {
        return $this->expenseRepository->deleteExpense($expense);
    }
}
