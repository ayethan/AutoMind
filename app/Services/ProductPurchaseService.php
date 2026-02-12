<?php

namespace App\Services;

use App\ProductPurchase;
use App\Repositories\ProductPurchaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth; // For getting authenticated user

class ProductPurchaseService
{
    protected $productPurchaseRepository;

    public function __construct(ProductPurchaseRepository $productPurchaseRepository)
    {
        $this->productPurchaseRepository = $productPurchaseRepository;
    }

    /**
     * Get all product purchases with pagination and relations.
     *
     * @return LengthAwarePaginator
     */
    public function paginateProductPurchases(): LengthAwarePaginator
    {
        return $this->productPurchaseRepository->paginateProductPurchases();
    }

    /**
     * Create a new product purchase.
     *
     * @param array $data
     * @return ProductPurchase
     */
    public function createProductPurchase(array $data): ProductPurchase
    {
        $userId = Auth::id(); // Get authenticated user's ID
        return $this->productPurchaseRepository->createProductPurchase($data, $userId);
    }

    /**
     * Find a product purchase by its model instance.
     *
     * @param ProductPurchase $productPurchase
     * @return ProductPurchase
     */
    public function findProductPurchase(ProductPurchase $productPurchase): ProductPurchase
    {
        return $this->productPurchaseRepository->findProductPurchase($productPurchase);
    }

    /**
     * Update an existing product purchase.
     *
     * @param ProductPurchase $productPurchase
     * @param array $data
     * @return ProductPurchase
     */
    public function updateProductPurchase(ProductPurchase $productPurchase, array $data): ProductPurchase
    {
        $userId = Auth::id(); // Get authenticated user's ID
        return $this->productPurchaseRepository->updateProductPurchase($productPurchase, $data, $userId);
    }

    /**
     * Delete a product purchase.
     *
     * @param ProductPurchase $productPurchase
     * @return bool|null
     */
    public function deleteProductPurchase(ProductPurchase $productPurchase): ?bool
    {
        return $this->productPurchaseRepository->deleteProductPurchase($productPurchase);
    }

    /**
     * Restore a soft-deleted product purchase.
     *
     * @param int $id
     * @return ProductPurchase
     */
    public function restoreProductPurchase(int $id): ProductPurchase
    {
        return $this->productPurchaseRepository->restoreProductPurchase($id);
    }
}
