<?php

namespace App\Repositories;

use App\Product;
use App\ProductPurchase;
use App\Utils\Helpers;
use Illuminate\Support\Facades\DB;

class ProductRepository
{
    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate()
    {
        return Product::paginate(Helpers::getValue('default-pagination'));
    }

    /**
     * @param string $query
     * @param int|null $take
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function search(string $query, ?int $take = null)
    {
        $queryBuilder = Product::where('code', 'like', "%{$query}%")->orWhere('name', 'like', "%{$query}%");
        if ($take) {
            $queryBuilder->take($take);
        }
        return $queryBuilder->get();
    }

    /**
     * @param array $data
     * @return Product
     */
    public function create(array $data): Product
    {
        $product = Product::create($data);
        $product->load(['category', 'sub_category']);
        return $product;
    }

    /**
     * @param Product $product
     * @return Product
     */
    public function find(Product $product): Product
    {
        return $product->load(["category", "sub_category", "category.sub_categories"]);
    }

    /**
     * @param Product $product
     * @param array $data
     * @return Product
     */
    public function update(Product $product, array $data): Product
    {
        $product->fill($data)->save();
        $product->load(['category', 'sub_category']);
        return $product;
    }

    /**
     * @param Product $product
     * @return bool|null
     */
    public function delete(Product $product): ?bool
    {
        return $product->delete();
    }

    /**
     * @param Product $product
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getPurchases(Product $product)
    {
        return $product->product_purchases()->paginate(Helpers::getValue('default-pagination'));
    }

    /**
     * @param Product $product
     * @param array $data
     * @return mixed
     */
    public function purchase(Product $product, array $data)
    {
        return DB::transaction(function () use ($product, $data) {
            $productPurchase = ProductPurchase::create([
                'qty' => $data['qty'],
                'buy_price' => $data['buy_price'],
                'sell_price' => $data['sell_price'],
                'product_id' => $product->id
            ]);

            $product->stock += $data['qty'];
            $product->buy_price = $data['buy_price'];
            $product->sell_price = $data['sell_price'];
            $product->save();

            return $productPurchase;
        });
    }
}
