<?php

namespace App\Repositories;

use App\Supplier;
use App\Utils\Helpers;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SupplierRepository
{
    /**
     * Get all suppliers with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateSuppliers(): LengthAwarePaginator
    {
        return Supplier::paginate(Helpers::getValue('default-pagination'));
    }

    /**
     * Create a new supplier.
     *
     * @param array $data
     * @return Supplier
     */
    public function createSupplier(array $data): Supplier
    {
        return Supplier::create($data);
    }

    /**
     * Find a supplier by its model instance.
     *
     * @param Supplier $supplier
     * @return Supplier
     */
    public function findSupplier(Supplier $supplier): Supplier
    {
        return $supplier;
    }

    /**
     * Update an existing supplier.
     *
     * @param Supplier $supplier
     * @param array $data
     * @return Supplier
     */
    public function updateSupplier(Supplier $supplier, array $data): Supplier
    {
        $supplier->fill($data)->save();
        return $supplier;
    }

    /**
     * Delete a supplier.
     *
     * @param Supplier $supplier
     * @return bool|null
     */
    public function deleteSupplier(Supplier $supplier): ?bool
    {
        return $supplier->delete();
    }
}
