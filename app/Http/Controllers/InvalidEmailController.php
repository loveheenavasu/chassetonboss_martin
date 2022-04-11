<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvalidEmail;
use DataTables;

class InvalidEmailController extends Controller
{
    public function index(Request $request)
    {
    	if ($request->ajax())
		{
			$allEmails = InvalidEmail::latest();
		    return Datatables::of($allEmails)
                ->addColumn('id', function ($row) {
                    return $row->id;
                })
                ->addColumn('email', function ($row) {
                    return $row->email;
                })
                ->addColumn('status', function ($row) {
                    return $row->status;
                })
                ->addColumn('type', function ($row) {
                    return $row->type;
                })
                ->addColumn('timezone', function ($row) {
                    return $row->timezone;
                })
                ->addColumn('rule_number', function ($row) {
                    return $row->rule_number;
                })
                ->addColumn('rule_name', function ($row) {
                    return $row->rule_name;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
               ->make(true);
        }
        return view('invalidemail.index');
    }
}
