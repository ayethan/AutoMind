<?php

namespace App\Http\Controllers\API;

use App\Utils\Helpers;
use App\ProductPurchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductPurchaseResource;

class ProductPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_purchases = ProductPurchase::with("car_made")
        ->paginate(Helpers::getValue('default-pagination'));
        return (ProductPurchaseResource::collection($product_purchases));
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
            "product_id" => "required",
            "qty" => "required|numeric",
            "buy_price" => "required|numeric"
        ]);

        $product_purchase = new ProductPurchase($request->all());
        $product_purchase->user_id = auth()->user()->id;
        $product_purchase->save();

        return (new ProductPurchaseResource($product_purchase))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductPurchase  $product_purchase
     * @return \Illuminate\Http\Response
     */
    public function show(ProductPurchase $product_purchase)
    {
        return (new ProductPurchaseResource($product_purchase));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ProductPurchase  $product_purchase
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductPurchase $product_purchase)
    {
        $this->validate($request, [
            "product_id" => "required",
            "qty" => "required|numeric",
            "buy_price" => "required|numeric"
        ]);

        $product_purchase->fill($request->all());
        $product_purchase->user_id = auth()->user()->id;
        $product_purchase->save();

        return (new ProductPurchaseResource($product_purchase))->response()->setStatusCode(202);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductPurchase  $product_purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductPurchase $product_purchase)
    {
        $product_purchase->delete();
        return (new ProductPurchaseResource($product_purchase))->response()->setStatusCode(202);
    }

    public function restore($id) {
        $product_purchase = ProductPurchase::withTrashed()->findOrFail($id);
        $product_purchase->restore();
        return (new ProductPurchaseResource($product_purchase));
    }
}
