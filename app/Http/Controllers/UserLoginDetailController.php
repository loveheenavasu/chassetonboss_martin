<?php

namespace App\Http\Controllers;
use App\Models\UserLoginDetails;
use Illuminate\Http\Request;
use DataTables;

class UserLoginDetailController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $allDetails = UserLoginDetails::latest();
            return Datatables::of($allDetails)
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('ip_address', function ($row) {
                    return $row->ip_address;
                })
                ->addColumn('timezone', function ($row) {
                    return $row->timezone;
                })
                ->addColumn('type', function ($row) {
                    return $row->type;
                })  
               ->make(true);
        }
        return view('user_login_logs.index');   
    }
}
