<?php

namespace App\Http\Controllers;

use App\Models\Product_media;
use App\Models\Product_size;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    function index($category_code)
    {
        $subcategories = Subcategory::fetch(0, [['category_code', $category_code]]);

        return view('contents.subcategories', compact('subcategories'));
    }
}