<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $subcategories = Subcategory::fetch(0, [['subcategory_id', 1]]);
        $categories    = Category::fetch(0, [['category_id', 1]]);
       return view('contents.products.index', compact('subcategories', 'categories'));
    }
}