<?php

namespace App\Http\Controllers;
use App\Models\Mauticlogs;
use Illuminate\Http\Request;
use DataTables;


class MauticLogsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax())
        {
            $allEmails = Mauticlogs::latest();
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
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
               ->make(true);
        }
        return view('mauticlogs.index');
    }
}
