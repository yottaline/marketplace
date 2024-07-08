<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        return view('contents.brands.index');
    }

    function load(Request $request)
    {
        $param  = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;
        if($request->code)  $param[]  = ['brand_code', $request->code];

        echo json_encode(Brand::fetch(0, $param, $limit, $lastId));
    }

    function submit(Request $request)
    {
        $id = $request->id;

        $params = [
            'brand_name'   => $request->name,
            'brand_status' => intval($request->status)
        ];

        if(!$id) $params['brand_code'] = uniqidReal(8);

        $result = Brand::submit($params, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Brand::fetch($result) : []
        ]);
    }
}