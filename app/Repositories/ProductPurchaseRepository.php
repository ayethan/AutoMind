<?php

namespace App\Repositories;

use App\ProductPurchase;
use App\Utils\Helpers;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ProductPurchaseRepository
{
    /**
     * Get all product purchases with pagination and relations.
     *
     * @return LengthAwarePaginator
     */
    public function paginateProductPurchases(): LengthAwarePaginator
    {
        return ProductPurchase::with("product")
            ->paginate(Helpers::getValue('default-pagination'));
    }

    /**
     * Create a new product purchase.
     *
     * @param array $data
     * @param int $userId
     * @return ProductPurchase
     */
    public function createProductPurchase(array $data, int $userId): ProductPurchase
    {
        $productPurchase = new ProductPurchase($data);
        $productPurchase->user_id = $userId;
        $productPurchase->save();
        return $productPurchase;
    }

    /**
     * Find a product purchase by its model instance.
     *
     * @param ProductPurchase $productPurchase
     * @return ProductPurchase
     */
    public function findProductPurchase(ProductPurchase $productPurchase): ProductPurchase
    {
        return $productPurchase;
    }

    /**
     * Update an existing product purchase.
     *
     * @param ProductPurchase $productPurchase
     * @param array $data
     * @param int $userId
     * @return ProductPurchase
     */
    public function updateProductPurchase(ProductPurchase $productPurchase, array $data, int $userId): ProductPurchase
    {
        $productPurchase->fill($data);
        $productPurchase->user_id = $userId;
        $productPurchase->save();
        return $productPurchase;
    }

    /**
     * Delete a product purchase.
     *
     * @param ProductPurchase $productPurchase
     * @return bool|null
     */
    public function deleteProductPurchase(ProductPurchase $productPurchase): ?bool
    {
        return $productPurchase->delete();
    }

    /**
     * Restore a soft-deleted product purchase.
     *
     * @param int $id
     * @return ProductPurchase
     */
    public function restoreProductPurchase(int $id): ProductPurchase
    {
        $productPurchase = ProductPurchase::withTrashed()->findOrFail($id);
        $productPurchase->restore();
        return $productPurchase;
    }
}
