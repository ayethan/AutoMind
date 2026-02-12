<?php

namespace App\Repositories;

use App\Expense;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExpenseRepository
{
    /**
     * Get all expenses with pagination, ordered by ID descending.
     *
     * @return LengthAwarePaginator
     */
    public function paginateExpenses(): LengthAwarePaginator
    {
        return Expense::orderBy('id', 'DESC')->paginate(config('tinyerp.default-pagination'));
    }

    /**
     * Create a new expense.
     *
     * @param array $data
     * @return Expense
     */
    public function createExpense(array $data): Expense
    {
        return Expense::create($data);
    }

    /**
     * Find an expense by its model instance.
     *
     * @param Expense $expense
     * @return Expense
     */
    public function findExpense(Expense $expense): Expense
    {
        return $expense;
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
        $expense->fill($data)->save();
        return $expense;
    }

    /**
     * Delete an expense.
     *
     * @param Expense $expense
     * @return bool|null
     */
    public function deleteExpense(Expense $expense): ?bool
    {
        return $expense->delete();
    }
}
