<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventInvalidEmail;
use DataTables;

class EventInvalidEmailController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()){
            $allEmails = EventInvalidEmail::latest();
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
                ->addColumn('event_id', function ($row) {
                    return $row->event_id;
                })
                ->addColumn('event_name', function ($row) {
                    return $row->event_name;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at;
                })
                ->addColumn('updated_at', function ($row) {
                    return $row->updated_at;
                })
               ->make(true);
        }
        return view('eventinvalidemail.index');
    }
    public function DeleteEventLogs_invalid_email(Request $request){
        if(isset($request->date)){
            $status = EventInvalidEmail::whereDate('created_at','=',$request->date)->delete();
            if($status){
                return 1;
            }else{
                return 0;
            }
        }else{
            $status = EventInvalidEmail::whereDate('created_at','<', Carbon::now())->delete();
            if($status){
                return 1;
            }else{
                return 0;
            }
        }
    }
}
