<?php

namespace App\Http\Controllers\API;

use App\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductPurchaseResource;
use App\Utils\Helpers;
use App\Services\ProductService; // new

class ProductController extends Controller
{
    protected $productService; // new

    public function __construct(ProductService $productService) // new
    {
        $this->productService = $productService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->productService->paginate(); // Updated to use service
        return ProductResource::collection($products);
    }


    /**
     * Search the products
     * 
     * 
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $products = collect([]);
        if($request->q) {
            $products = $this->productService->search($request->q, $request->take); // Updated to use service
        }
        return ProductResource::collection($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required|unique:products,code',
            'name' => 'required',
            'buy_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
            'stock' => 'required|numeric'
        ]);
        $product = $this->productService->create($request->all()); // Updated to use service
        return new ProductResource($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        $product = $this->productService->find($product); // Updated to use service
        return new ProductResource($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->validate($request, [
            'code' => 'required|unique:products,code,'.$product->id,
            'name' => 'required',
            'buy_price' => 'numeric',
            'sell_price' => 'numeric',
            'stock' => 'numeric'
        ]);
        
        $product = $this->productService->update($product, $request->all()); // Updated to use service
        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  App\Product $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $this->productService->delete($product); // Updated to use service
        return new ProductResource($product);
    }

    /**
     * Add the stock of the product
     * 
     * @param \Illuminate\Http\Request $request
     * @param App\Product $product
     * 
     * @return \Illuminate\Http\Response
     * 
     */
    public function purchase(Request $request, Product $product) {
        $this->validate($request, [
            'qty' => 'required|numeric',
            'buy_price' => 'required|numeric',
            'sell_price' => 'required|numeric',
        ]);

        try {
            $product_purchase = $this->productService->purchase($product, $request->only(['qty', 'buy_price', 'sell_price'])); // Updated to use service
            return (new ProductPurchaseResource($product_purchase))->response()->setStatusCode(201);
        } catch(Exception $e) {
            // No longer need DB::rollback here as the service handles the transaction.
            abort(500, 'Server error: ' . $e->getMessage()); // Added exception message for debugging
        }
    }

    /**
     * Get the related purchases of the product
     * @param App\Product $product
     * 
     * @return App\Http\Resources\ProductPurchaseResource
     * 
     */
    public function getPurchases(Product $product) {
        $product_purchases = $this->productService->getPurchases($product); // Updated to use service
        return ProductPurchaseResource::collection($product_purchases);
    }
}
