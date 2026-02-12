<?php

namespace App\Http\Controllers\API;

use App\Product;
use App\ProductPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\ProductPurchaseResource;
use App\Utils\Helpers;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::paginate(Helpers::getValue('default-pagination'));
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
            $q_builder = Product::where('code', 'like', "%{$request->q}%")->orWhere('name', 'like', "%{$request->q}%");
            if($request->take) {
                $q_builder->take($request->take);
            }
            $products = $q_builder->get();
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
        $product = Product::create($request->all());
        $product->load(['category', 'sub_category']);
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
        $product->load(["category", "sub_category", "category.sub_categories"]);
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
        
        $product->fill($request->all())->save();
        $product->load(['category', 'sub_category']);
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
        $product->delete();
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
            DB::beginTransaction();
            $product_purchase = ProductPurchase::create([
                'qty' => $request->qty,
                'buy_price' => $request->buy_price,
                'sell_price' => $request->sell_price,
                'product_id' => $product->id
            ]);

            $product->buy_price = $request->buy_price;
            $product->sell_price = $request->sell_price;
            $product->stock += $request->qty;
            $product->save();
            $product->load('product_purchases');
            
            DB::commit();
            return (new ProductPurchaseResource($product_purchase))->response()->setStatusCode(201);
        } catch(Exception $e) {
            DB::rolback();
            abort(500, 'Server error');
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
        $product_purchases = $product->product_purchases()->paginate(Helpers::getValue('default-pagination'));
        return ProductPurchaseResource::collection($product_purchases);
    }
} 
