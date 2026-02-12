<?php

namespace App\Services;

use App\Product;
use App\Repositories\ProductRepository;
use App\Http\Resources\ProductPurchaseResource;
use Exception;

class ProductService
{
    protected $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Get all products with pagination.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate()
    {
        return $this->productRepository->paginate();
    }

    /**
     * Search products by code or name.
     *
     * @param string $query
     * @param int|null $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $query, ?int $take = null)
    {
        return $this->productRepository->search($query, $take);
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    /**
     * Find a product by its model instance and load its relations.
     *
     * @param Product $product
     * @return Product
     */
    public function find(Product $product): Product
    {
        return $this->productRepository->find($product);
    }

    /**
     * Update an existing product.
     *
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function update(Product $product, array $data): Product
    {
        return $this->productRepository->update($product, $data);
    }

    /**
     * Delete a product.
     *
     * @param Product $product
     * @return bool|null
     */
    public function delete(Product $product): ?bool
    {
        return $this->productRepository->delete($product);
    }

    /**
     * Record a product purchase, updating product stock and prices.
     *
     * @param Product $product
     * @param array $data
     * @return mixed
     * @throws Exception
     */
    public function purchase(Product $product, array $data)
    {
        try {
            return $this->productRepository->purchase($product, $data);
        } catch (Exception $e) {
            // Log the exception if needed
            throw new Exception('Failed to record product purchase: ' . $e->getMessage());
        }
    }

    /**
     * Get related purchases of a product with pagination.
     *
     * @param Product $product
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPurchases(Product $product)
    {
        return $this->productRepository->getPurchases($product);
    }
}
