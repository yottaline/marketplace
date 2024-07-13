<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        return view('contents.customers.index');
    }

    function load(Request $request)
    {
        $param  = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;

        echo json_encode(Customer::fetch(0, $param, $limit, $lastId));
    }

    function submit(Request $request)
    {
        $id = $request->id;
        $email = $request->email;
        $phone = $request->phone;

        if (count(Customer::fetch(0, [['customer_id', '!=', $id], ['customer_phone', $phone]]))) {
            echo json_encode(['status' => false, 'message' => __('Phone number already exists'),]);
            return;
        }

        if ($email &&  count(Customer::fetch(0, [['customer_id', '!=', $id], ['customer_email', $email]]))) {
            echo json_encode(['status' => false, 'message' => __('Email already exists'),]);
            return;
        }

        $params = [
            'customer_name'  => $request->name,
            'customer_email' => $email,
            'customer_phone' => $phone,
            'customer_address' => $request->address,
            'customer_status'  => intval($request->status)
        ];

        if(!$id){
            $param['customer_code']     = uniqidReal(8);
            $param['customer_password'] = Hash::make($request->password);
            $param['customer_created']  = Carbon::now();
        }

        $result = Customer::submit($params, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Customer::fetch($result) : []
        ]);
    }
}