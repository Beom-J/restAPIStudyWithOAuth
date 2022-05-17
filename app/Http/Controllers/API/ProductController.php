<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        // ProductResource 가 모델 & 컬렉션을 json 형식으로 바꿔줌
        return response(["products" => ProductResource::collection($products)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            "name" => "required|max:255",
            "sku" => "required|max:255",
            "upc" => "required|max:255",
        ]);

        if ($validator->fails()) {
            return response([
                "error" => $validator->errors(),
                "Validation Error",
            ]);
        }

        $product = Product::create($data);

        return response([
            "product" => new ProductResource($product),
            "message" => "Product created successfully",
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Modles\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return response(["product" => new ProductResource($product)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Modles\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            "name" => "required|max:255",
            "sku" => "required|max:255",
            "upc" => "required|max:255",
        ]);

        if ($validator->fails()) {
            return response([
                "error" => $validator->errors(),
                "Validation Error",
            ]);
        }
        $product->update($data);

        return response([
            "product" => new ProductResource($product),
            "message" => "Product updated successfully",
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Modles\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response(["message" => "Product deleted successfully"]);
    }
}
