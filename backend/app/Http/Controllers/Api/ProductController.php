<?php

namespace App\Http\Controllers\Api;

use App\Filament\Resources\ProductResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductListResource;
use App\Models\Product;
use App\ProductStatusEnum;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('status', ProductStatusEnum::Published)->get();
        return ProductListResource::collection($products);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id)->load(['category', 'department', 'variants', 'media']);
        if ($product->status !== 'published') {
            abort(404, 'Product not found or not available');
        }
        return $product->toResource();
    }

}

