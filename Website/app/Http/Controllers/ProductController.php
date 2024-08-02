<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product_size;

class ProductController extends Controller
{
    function fetch(Request $request)
    {
        $subcategory_id =  $request->sub ? $request->sub[0] : 0;
        $params = $request->q ? ['q' => $request->q] : [];
        if($subcategory_id) $params[] = ['product_category', $subcategory_id];
        $lastId = $request->last_id;
        $params[] = ['prodsize_status', 1];

        echo json_encode(Product_size::fetch(0, $params, null, $lastId));
    }

    function bestSeller(Request $request)
    {
        $params = $request->q ? ['q' => $request->q] : [];
        $params[] = ['prodsize_count_purchased', '>', 0];
        $lastId = $request->last_id;

        echo json_encode(Product_size::fetch(0, $params, null, $lastId));

    }

    function cart()
    {
        return view('contents.cart');
    }
}