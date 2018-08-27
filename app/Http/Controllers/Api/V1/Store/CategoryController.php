<?php

namespace App\Http\Controllers\Api\V1\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Category list
     * 
     * @return mixed
     */
    public function index()
    {
        $categories = Category::query()->withCount('products');

        return $categories->get();
    }

    /**
     * View specific category
     * 
     * @param  Product $model Product model
     * @return Product
     */
    public function view(Request $request, Category $model)
    {
        return $model;
    }
}
