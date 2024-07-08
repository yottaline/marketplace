<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $categories = Category::fetch(0, [['category_status', 1]]);
        return view('contents.subcategories.index', compact('categories')) ;
    }

    function load(Request $request)
    {
        $param  = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;
        if ($request->code)  $param[]  = ['subcategory_code', $request->code];

        echo json_encode(Subcategory::fetch(0, $param, $limit, $lastId));
    }

    function submit(Request $request)
    {
        $id = $request->id;

        $params = [
            'subcategory_name'     => $request->name,
            'subcategory_category' => $request->category,
            'subcategory_status'   => intval($request->status)
        ];
        if(!$id) $params['subcategory_code'] = uniqidReal(8);

        $result = Subcategory::submit($params, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Subcategory::fetch($result) : []
        ]);
    }
}