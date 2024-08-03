<?php

namespace App\Http\Controllers;

use App\Models\Size;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $subcategories = Subcategory::fetch(0, [['subcategory_status', 1]]);
        return view('contents.sizes.index', compact('subcategories'));
    }

    function load(Request $request)
    {
        $param  = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;
        if ($request->code)  $param[]  = ['size_code', $request->code];

        echo json_encode(Size::fetch(0, $param, $limit, $lastId));
    }

    function submit(Request $request)
    {
        $id = $request->id;

        $params = [
            'size_name'        => $request->name,
            'size_sign'        => $request->sign,
            'size_subcategory' => $request->subcategory,
            'size_status'      => $request->status ? $request->status : 1
        ];
        if(!$id) $params['size_code'] = uniqidReal(8);

        $result = Size::submit($params, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Size::fetch($result) : []
        ]);
    }
}