<?php

namespace App\Http\Controllers\API;

use App\ProductPurchase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductPurchaseResource;
use App\Services\ProductPurchaseService;
use Exception;

class ProductPurchaseController extends Controller
{
    protected $productPurchaseService;

    public function __construct(ProductPurchaseService $productPurchaseService)
    {
        $this->productPurchaseService = $productPurchaseService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $product_purchases = $this->productPurchaseService->paginateProductPurchases();
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

        try {
            $product_purchase = $this->productPurchaseService->createProductPurchase($request->all());
            return (new ProductPurchaseResource($product_purchase))->response()->setStatusCode(201);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ProductPurchase  $product_purchase
     * @return \Illuminate\Http\Response
     */
    public function show(ProductPurchase $product_purchase)
    {
        $product_purchase = $this->productPurchaseService->findProductPurchase($product_purchase);
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

        try {
            $product_purchase = $this->productPurchaseService->updateProductPurchase($product_purchase, $request->all());
            return (new ProductPurchaseResource($product_purchase))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ProductPurchase  $product_purchase
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductPurchase $product_purchase)
    {
        try {
            $this->productPurchaseService->deleteProductPurchase($product_purchase);
            return (new ProductPurchaseResource($product_purchase))->response()->setStatusCode(202);
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }

    public function restore($id) {
        try {
            $product_purchase = $this->productPurchaseService->restoreProductPurchase($id);
            return (new ProductPurchaseResource($product_purchase));
        } catch (Exception $e) {
            abort(500, 'Server Error: ' . $e->getMessage());
        }
    }
}
