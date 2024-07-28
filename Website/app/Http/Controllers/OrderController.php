<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\Product_size;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    function create(Request $request)
    {
        $ids = explode(',', $request->sizes);
        $qty = explode(',', $request->qty);
        $disc =  0;

        $ordSubtotal = $orderTotalDisc = $ordTotal = 0;
        $orderParam = [];
        $products  = Product_size::fetch(0, null, $ids);
        foreach ($products as $p) {
            $indx = array_search($p->prodsize_id, $ids);
            if ($indx !== false) {
                $request_qty = ($p->prodsize_qty - $qty[$indx]);
                Product_size::updateSize($p->prodsize_id, ['prodsize_qty' => $request_qty]);
                $subtotal = $qty[$indx] * $p->prodsize_sellprice;
                $total    = $subtotal * $disc / 100;
                $orderProductParam[] = [
                    'orderItem_product'       => $p->product_id,
                    'orderItem_size'          => $p->prodsize_id,
                    'orderItem_productPrice'  => $p->prodsize_sellprice,
                    'orderItem_qty'           => $qty[$indx],
                    'orderItem_subtotal'      => $subtotal,
                    'orderItem_total'         => $total,
                    'orderItem_disc'          => 0
                ];
                $ordSubtotal    += $subtotal;
                $ordTotal       += $subtotal;
            }
        }

        $orderParam = [
            'order_code'          => Str::random(12),
            'order_customer'      => $request->customer,
            'order_subtotal'      => $ordSubtotal,
            'order_discount'      => 0,
            'order_total'         => $ordTotal,
            'order_status'        =>  1,
            'order_created'       => Carbon::now(),
        ];

        $result = Order::submit(0, $orderParam, $orderProductParam);
        return $result;
    }
}