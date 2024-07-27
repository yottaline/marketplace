<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class CustomerController extends Controller
{
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
            'customer_status'  => 1,
            'customer_password' =>  Hash::make($request->password)
        ];

        if(!$id){
            $params['customer_code']     =  Str::random(8);
            $params['customer_created']  = Carbon::now();
        }

        $result = Customer::submit($params, $id);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Customer::fetch($result) : []
        ]);
    }
}