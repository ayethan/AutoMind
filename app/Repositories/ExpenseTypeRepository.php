<?php

namespace App\Repositories;

use App\ExpenseType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExpenseTypeRepository
{
    /**
     * Get all expense types with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateExpenseTypes(): LengthAwarePaginator
    {
        return ExpenseType::paginate(config('tinyerp.default-pagination'));
    }

    /**
     * Get all expense types.
     *
     * @return Collection
     */
    public function getAllExpenseTypes(): Collection
    {
        return ExpenseType::all();
    }

    /**
     * Create a new expense type.
     *
     * @param array $data
     * @return ExpenseType
     */
    public function createExpenseType(array $data): ExpenseType
    {
        return ExpenseType::create($data);
    }

    /**
     * Find an expense type by its model instance.
     *
     * @param ExpenseType $expenseType
     * @return ExpenseType
     */
    public function findExpenseType(ExpenseType $expenseType): ExpenseType
    {
        return $expenseType;
    }

    /**
     * Update an existing expense type.
     *
     * @param ExpenseType $expenseType
     * @param array $data
     * @return ExpenseType
     */
    public function updateExpenseType(ExpenseType $expenseType, array $data): ExpenseType
    {
        $expenseType->fill($data)->save();
        return $expenseType;
    }

    /**
     * Delete an expense type.
     *
     * @param ExpenseType $expenseType
     * @return bool|null
     */
    public function deleteExpenseType(ExpenseType $expenseType): ?bool
    {
        return $expenseType->delete();
    }
}
