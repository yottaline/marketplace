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
        if(auth()->user()->user_type == 2) {
            $param[] = ['order_create_by', auth()->user()->id];
        }
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
                Product_size::submit($p->prodsize_id, [['prodsize_qty' => $qty[$indx]], ['prodsize_count_purchased' => $p->prodsize_count_purchased +1]]);
                $subtotal = $qty[$indx] * $p->prodsize_price;
                $total    = $subtotal * $disc / 100;
                $orderProductParam[] = [
                    'orderItem_product'       => $p->product_id,
                    'orderItem_size'          => $p->prodsize_id,
                    'orderItem_productPrice'  => $p->prodsize_price,
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
        $products = Order_item::fetch(0, [['orderItem_order', $order->order_id]]);
        return view('contents.orders.view', compact('order', 'customer', 'products'));
    }

    function updateStatus(Request $request)
    {
        $param = [
            'order_status' => $request->status,
        ];
        if ($request->status == 3) $param['order_placed'] = Carbon::now();

        $result =  Order::submit($request->id, $param);
        echo json_encode([
            'status'  => boolval($result),
            'data'    => $result ? Order::fetch($request->id) : []
        ]);
    }

    function delSize(Request $request)
    {
        $order = Order::fetch($request->order);
        $products = Order_item::fetch(0, [['orderItem_order', $request->order]]);
        $ndx = 0;
        $order->order_subtotal = 0;
        $order->order_total = 0;
        for ($i = 0; $i < count($products); $i++) {
            if ($products[$i]->orderItem_subtotal != $request->size) {
                $order->order_subtotal += $products[$i]->orderItem_subtotal;
                $order->order_total += $products[$i]->orderItem_total;
            } else $ndx = $i;
        }
        unset($products[$ndx]);
        $orderParam = [
            'order_subtotal' => $order->order_subtotal,
            'order_total' => $order->order_total,
        ];

        $result =  Order_item::delSize($request->size, [$request->order, $orderParam]);
        echo json_encode(array_merge($result, [
            'order' => $order,
            'products' => $products,
        ]));
    }

    function updateQty(Request $request)
    {
        $order = Order::fetch($request->order);
        $products = Order_item::fetch(0, [['orderItem_order', $request->order]]);

        $ndx = 0;
        $order->order_subtotal = 0;
        $order->order_total = 0;
        for ($i = 0; $i < count($products); $i++) {
            if ($products[$i]->orderItem_id == $request->product) {
                $products[$i]->orderItem_qty = $request->qty;
                $ndx = $i;
            }
            $products[$i]->orderItem_subtotal = $products[$i]->orderItem_qty * $products[$i]->orderItem_productPrice;
            $products[$i]->orderItem_total = $products[$i]->orderItem_subtotal - ($products[$i]->orderItem_subtotal * $products[$i]->orderItem_discount / 100);

            $order->order_subtotal += $products[$i]->orderItem_subtotal;
            $order->order_total += $products[$i]->orderItem_total;
        }
        $param = [
            'orderItem_qty' => $request->qty,
            'orderItem_subtotal' => $products[$ndx]->orderItem_subtotal,
            'orderItem_total' => $products[$ndx]->orderItem_total,
        ];
        if (!$request->target) {
            $param['orderItem_qty'] = $request->qty;
            $products[$ndx]->orderItem_request_qty = $request->qty;
        };
        $orderParam = [
            'order_subtotal' => $order->order_subtotal,
            'order_total' => $order->order_total,
        ];

        $result =  Order_item::submit([$request->product, $param], [$request->order, $orderParam]);
        echo json_encode(array_merge($result, [
            'order' => $order,
            'products' => $products,
        ]));
    }
}