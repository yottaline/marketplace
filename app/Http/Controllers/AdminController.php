<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    function __construct()
    {
        $this->middleware('role:admin');
    }

    function index()
    {
        return view('contents.users.index');
    }

    function load(Request $request)
    {
        $param = $request->q ? ['q' => $request->q] : [];
        $limit = $request->limit;
        $lastId = $request->last_id;
        echo json_encode(Admin::fetch(0, $param, null, $limit, $lastId));
    }

    function submit(Request $request)
    {
        // return $request;
        $id = intval($request->id);
        $param = [
            'user_name'    => $request->name,
            'user_email'   => $request->email,
            'user_password' => Hash::make($request->password)
        ];

        if (!$id) {
            $param['user_code'] = uniqidReal(8);
            $param['user_created'] = Carbon::now();
            $param['user_type'] = 1;
        } else {
            $param['user_modified'] = Carbon::now();
        }

        $result = Admin::submit($param, $id);
        echo json_encode([
            'status' => boolval($result),
            'data' => $result ? Admin::fetch($id) : [],
        ]);
    }
}