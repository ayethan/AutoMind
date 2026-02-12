<?php

namespace App\Services;

use App\Supplier;
use App\Repositories\SupplierRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SupplierService
{
    protected $supplierRepository;

    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * Get all suppliers with pagination.
     *
     * @return LengthAwarePaginator
     */
    public function paginateSuppliers(): LengthAwarePaginator
    {
        return $this->supplierRepository->paginateSuppliers();
    }

    /**
     * Create a new supplier.
     *
     * @param array $data
     * @return Supplier
     */
    public function createSupplier(array $data): Supplier
    {
        return $this->supplierRepository->createSupplier($data);
    }

    /**
     * Find a supplier by its model instance.
     *
     * @param Supplier $supplier
     * @return Supplier
     */
    public function findSupplier(Supplier $supplier): Supplier
    {
        return $this->supplierRepository->findSupplier($supplier);
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
        return $this->supplierRepository->updateSupplier($supplier, $data);
    }

    /**
     * Delete a supplier.
     *
     * @param Supplier $supplier
     * @return bool|null
     */
    public function deleteSupplier(Supplier $supplier): ?bool
    {
        return $this->supplierRepository->deleteSupplier($supplier);
    }
}
