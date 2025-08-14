<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\ProductStatusEnum;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return Product::where('status', ProductStatusEnum::Published)->get()->toResourceCollection();
    }

    public function show($id)
    {
        // Logic to retrieve a single product by ID
    }

}
