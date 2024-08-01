<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $brands = Brand::fetch(0, [['brand_status', 1]]);
        return view('contents.categories.index', compact('brands'));
    }

    function load(Request $request)
    {
        $param  = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;
        if ($request->code)  $param[]  = ['category_code', $request->code];

        echo json_encode(Category::fetch(0, $param, $limit, $lastId));
    }

    function submit(Request $request)
    {
        $id = $request->id;

        $params = [
            'category_name'   => $request->name,
            'category_brand'  => $request->brand,
            'category_status' => $request->status ? $request->status : 1
        ];

        if(!$id) $params['category_code'] = uniqidReal(8);

        $result = Category::submit($params, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Category::fetch($result) : []
        ]);
    }
}