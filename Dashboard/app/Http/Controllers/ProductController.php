<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
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
        $categories    = Category::fetch(0, [['category_status', 1]]);
        $brands        = Brand::fetch(0,[['brand_status', 1]]);
       return view('contents.products.index', compact('categories', 'brands'));
    }

    function load(Request $request)
    {
        $params = $request->q ? ['q' => $request->q] : [];
        $params[] = ['product_created_by', auth()->user()->id];
        if ($request->category) $params[] = ['product_category', $request->category];
        echo json_encode(Product::fetch(0, $params, $request->limit, $request->offset));
    }

    function getCategory($brand_id)
    {
        echo json_encode(Category::fetch(0, [['brand_id', $brand_id]]));
    }

    function getSubcategory($category_id)
    {
        echo json_encode(Subcategory::fetch(0, [['category_id', $category_id]]));
    }

    function submit(Request $request)
    {
        $id = $request->id;
        $params = [
            'product_code'         => $request->code,
            'product_name'         => $request->name,
            'product_desc'         => $request?->context,
            'product_category'     => $request->category,
            'product_subcategory'  => $request->subcategory,
            'product_created_by'   => auth()->user()->id
        ];

        $result = Product::submit($params, $id);
        echo json_encode([
            'status'   => boolval($result),
            'data'     => $result ? Product::fetch($result) : []
        ]);
    }

    function view($product_code)
    {
        $products = Product::fetch(0, [['product_code', $product_code]]);
        $product = $products[0];
        return view('contents.products.view', compact('product'));
    }
}