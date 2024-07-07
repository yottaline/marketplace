<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Retailer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class RetailerController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware(['role:admin']);
    // }

    function index()
    {
        return view('contents.retailers.index');
    }

    function load(Request $request)
    {

        $param  = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $lastId = $request->last_id;
        if ($request->status)  $param[]  = ['retailer_approved', $request->status - 1];

        echo json_encode(Retailer::fetch(0, $param, null, $limit, $lastId));
    }

    function submit(Request $request)
    {

        $id    = $request->retailer_id;
        $email = $request->email;
        $phone = $request->phone;


        if (count(Retailer::fetch(0, [['retailer_id', '!=', $id], ['retailer_phone', $phone]]))) {
            echo json_encode(['status' => false, 'message' => __('Phone number already exists'),]);
            return;
        }

        if (count(Retailer::fetch(0, [['retailer_id', '!=', $id], ['user_email', $email]]))) {
            echo json_encode(['status' => false, 'message' => __('Email already exists'),]);
            return;
        }

        $userParam = [
            'user_code'     => uniqidReal(8),
            'user_name'     => $request->name,
            'user_email'    => $email,
            'user_password' => Hash::make($request->password),
            'user_type'     => 2,
            'user_created'  => Carbon::now()
        ];

        $retailerParam = [
            'retailer_phone'   => $phone,
            'retailer_store'   => $request?->store_name,
            'retailer_mobile'  => $request?->store_mobile,
            'retailer_note'    => '',
            'retailer_address' => $request->address,
            'retailer_vat'     => intval($request->vat),
            'retailer_approved' => Carbon::now(),
            'retailer_approved_by' =>  auth()->user()->id,
        ];

        $result = Retailer::submit($id, $userParam, $retailerParam);
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Retailer::fetch($result) : []
        ]);
    }

}