<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();
        if ($products->count() > 0) {
            return ProductResource::collection($products);
        } else {
            return response()->json(['message' => 'No data'], 200);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'loi',
                'error' => $validator->messages(),
            ], 422);
        }
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'tao san pham thanh cong',
            'product' => new ProductResource($product)
        ], 201);
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }



    public function update(Request $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'loi',
                'error' => $validator->messages(),
            ], 422);
        }
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
        ]);

        return response()->json([
            'message' => 'cap nhat san pham thanh cong',
            'product' => new ProductResource($product)
        ], 200);
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->json([
            'message' => 'xoa san pham thanh cong',
        ], 200);
    }
}