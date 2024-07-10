<?php

namespace App\Http\Controllers;

use App\Models\Product_media;
use Illuminate\Http\Request;

class ProductMediaController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function load(Request $request)
    {
        echo json_encode(Product_media::fetch(0, [['product_id', $request->product_id]]));
    }


    function submit(Request $request)
    {
        $id = $request->id;

        $param = [
            'product_id'  => $request->product_id,
            'media_type'  => 'image',
        ];

        $media = $request->file('media');
        if ($media && count($media) > 0) {
            foreach ($media as $image) {
                $fileName = uniqidReal(8) . '.' . $image->getClientOriginalExtension();
                $image->move('media/product/'. $request->product_id .'/' , $fileName);
                $param['media_url'] = $fileName;
                $result = Product_media::submit($param, $id);
            }
        }

        echo json_encode([
            'status' => boolval($result),
            'data' => $result ? Product_media::fetch(0, [['media_id', $result],['product_id', $request->product_id]]) : []
        ]);
    }
}