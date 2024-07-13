<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use App\Models\Product_size;
use App\Models\Order_item;

class OrderController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $customers = Customer::fetch(0, [['customer_status', 1]]);
        return view('contents.orders.index', compact('customers'));
    }

    function create()
    {
        $customers = Customer::fetch(0, [['customer_status', 1]]);
        return view('contents.orders.create', compact('customers'));
    }

    function load(Request $request)
    {
        $param = $request->q ? ['q' => $request->q] : [];
        $limit = $request->limit;
        $lastId = $request->last_id;
        if ($request->date)   $param[] = ['order_created', 'like', '%' . $request->date . '%'];
        if ($request->r_name) $param[] = ['customer_name', 'like', '%' . $request->r_name . '%'];

        echo json_encode(Order::fetch(0, $param, $limit, $lastId));
    }

    function submit(Request $request)
    {
        $ids = explode(',', $request->sizes);
        $qty = explode(',', $request->qty);
        $disc =  $request->disc;

        $customer = Customer::fetch(0, [['customer_email', $request->email]]);
        if (!count($customer)) {
            $customerParam = [
                'customer_code' => uniqidReal(8),
                'customer_email'    => $request->email,
                'customer_password' => Hash::make('1234'),
                'customer_name' => $request->name,
                'customer_phone'    => $request->phone ?? '',
                'customer_address'  => $request->address ?? '',
                'customer_created'  => Carbon::now(),
            ];

            $status = Customer::submit($customerParam, null);
            $customer = Customer::fetch(0, [['customer_id', $status]]);
        }

        $ordSubtotal = $orderTotalDisc = $ordTotal = 0;
        $orderParam = [];
        $products  = Product_size::fetch(0, null, $ids);
        foreach ($products as $p) {
            $indx = array_search($p->prodsize_id, $ids);
            if ($indx !== false) {
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
            'order_code',
            'order_code'          => uniqidReal(12),
            'order_customer'      => $customer[0]->customer_id,
            'order_subtotal'      => $ordSubtotal,
            'order_discount'      => 0,
            'order_total'         => $ordTotal,
            'order_status'        =>  4,
            'order_note'          => $request?->note,
            'order_create_by'     => auth()->user()->id,
            'order_created'       => Carbon::now(),
            'order_approved'      => Carbon::now(),
        ];

        $result = Order::submit(0, $orderParam, $orderProductParam);

        if ($result['status']) $result['data'] = Order::fetch($result['id']);
        echo json_encode($result);
    }

    function view($code)
    {
        $order = Order::fetch($code);
        $customer = Customer::fetch($order->order_customer);
        $products = Order_item::fetch(0, [['order_code', $code]]);

        return view('contents.orders.view', compact('order', 'customer', 'products'));
    }
}