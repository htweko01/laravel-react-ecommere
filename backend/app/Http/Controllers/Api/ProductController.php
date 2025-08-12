<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        return Product::all()->toResourceCollection();
    }

    public function show($id)
    {
        // Logic to retrieve a single product by ID
    }

}
