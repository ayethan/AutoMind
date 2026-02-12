<?php

namespace App\Services;

use App\ExpenseType;
use App\Repositories\ExpenseTypeRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ExpenseTypeService
{
    protected $expenseTypeRepository;

    public function __construct(ExpenseTypeRepository $expenseTypeRepository)
    {
        $this->expenseTypeRepository = $expenseTypeRepository;
    }

    /**
     * Get all expense types with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateExpenseTypes(): LengthAwarePaginator
    {
        return $this->expenseTypeRepository->paginateExpenseTypes();
    }

    /**
     * Get all expense types.
     *
     * @return Collection
     */
    public function getAllExpenseTypes(): Collection
    {
        return $this->expenseTypeRepository->getAllExpenseTypes();
    }

    /**
     * Create a new expense type.
     *
     * @param array $data
     * @return ExpenseType
     */
    public function createExpenseType(array $data): ExpenseType
    {
        return $this->expenseTypeRepository->createExpenseType($data);
    }

    /**
     * Find an expense type by its model instance.
     *
     * @param ExpenseType $expenseType
     * @return ExpenseType
     */
    public function findExpenseType(ExpenseType $expenseType): ExpenseType
    {
        return $this->expenseTypeRepository->findExpenseType($expenseType);
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
        return $this->expenseTypeRepository->updateExpenseType($expenseType, $data);
    }

    /**
     * Delete an expense type.
     *
     * @param ExpenseType $expenseType
     * @return bool|null
     */
    public function deleteExpenseType(ExpenseType $expenseType): ?bool
    {
        return $this->expenseTypeRepository->deleteExpenseType($expenseType);
    }
}
