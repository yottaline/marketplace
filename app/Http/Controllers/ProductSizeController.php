<?php

namespace App\Http\Controllers;

use App\Models\Product_color;
use App\Models\Product_size;
use Illuminate\Http\Request;

class ProductSizeController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function load(Request $request)
    {
        echo json_encode(Product_size::fetch(0, [['prodsize_size', $request->product_id]]));
    }

    function submit(Request $request)
    {
        $color_name = explode(',', $request->name);
        $sizes      = $request->size;

        $colorParam = [];
        $sizeParam  = [];

        $color_name = array_values(array_filter($color_name, function ($e) {
            $e = trim($e);
            return !empty($e);
        }));

        $color_ref = uniqidReal(8);
        if (count($color_name) > 1) {
            foreach ($color_name as $color) {
                $colorParam[] = [
                    'prodcolor_name'        => $color,
                    'prodcolor_code'        => $color_ref,
                    'prodcolor_minqty'      => $request->min,
                    'prodcolor_maxqty'      => $request->max,
                    'prodcolor_media'       => 0,
                    'prodcolor_product'     => $request->p_id,
                ];
                foreach ($sizes as $size) {
                    $sizeParam[] = [
                        'prodsize_size'            => $size,
                        'prodsize_code'            => uniqidReal(8),
                        'prodsize_product'         => $request->p_id,
                        'prodsize_color'           =>  $color_ref,
                        'prodsize_sellprice'       => $request->sell,
                        'prodsize_price'           => $request->price,
                        'prodsize_qty'             => $request->qty,
                        'prodsize_discount'        => $request->discount ?? 0,
                        'prodsize_discount_start'  => $request->start,
                        'prodsize_discount_end'    => $request->end
                    ];
                };
            }
        } else {

                $colorParam[] = [
                    'prodcolor_code'         => $color_ref,
                    'prodcolor_name'        => $request->name,
                    'prodcolor_minqty'      => $request->min,
                    'prodcolor_maxqty'      => $request->max,
                    'prodcolor_media'       => 0,
                    'prodcolor_product'     => $request->p_id,
                ];

                foreach ($sizes as $size) {
                    $sizeParam[] = [
                        'prodsize_size'            => $size,
                        'prodsize_code'           => uniqidReal(8),
                        'prodsize_product'         => $request->p_id,
                        'prodsize_color'           =>  $color_ref,
                        'prodsize_sellprice'       => $request->sell,
                        'prodsize_price'           => $request->price,
                        'prodsize_qty'             => $request->qty,
                        'prodsize_discount'        => $request->discount ?? 0,
                        'prodsize_discount_start'  => $request->start,
                        'prodsize_discount_end'    => $request->end
                    ];
                };
        }

        $result = Product_color::createProduct($colorParam, $sizeParam);

        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ?  Product_size::fetch($result, [['prodsize_product', $request->p_id]]) : []
        ]);
    }

    function update(Request $request)
    {
        $id = $request->id;

        $params = [
            'prodsize_cost'           => $request->cost,
            'prodsize_sellprice'      => $request->sell,
            'prodsize_price'          => $request->price,
            'prodsize_qty'            => $request->qty,
            'prodsize_discount'       => $request->discount,
            'prodsize_discount_start' => $request->start,
            'prodsize_discount_end'   => $request->end,
            'prodsize_status'         => $request->status ?? 1
        ];

        $result = Product_size::submit($params, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ?  Product_size::fetch($result, [['prodsize_product', $request->p_id]]) : []
        ]);
    }
}