<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmailLogs;
use App\Models\InvalidEmail;
use App\Models\WebhookCron;
use DataTables;
use Illuminate\Support\Facades\File; 


class EmailLogsController extends Controller
{
    public function index(Request $request)
    {
    	if ($request->ajax())
		{
			$allEmails = EmailLogs::latest();

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
        return view('emaillogs.index');
    }

    public function DeleteEmailLogs(Request $request){
         
        $status = EmailLogs::whereDate('created_at','=',$request->date)->delete();
        if($status){
            return 1;
        }else{
            return 0;
        }
    }

     public function SetLogsDeleteCron(Request $request){

         $hours = $request['hours'];
         $exits = WebhookCron::where('id',1)->first();
         if($exits){
            $update = WebhookCron::where('id', 1)
              ->update([
               'cron_time' => $hours,
               'status'  =>'yes'
             ]); 
              if($update){
                 return 1;
              }else{
                return 0;
              }
            
         }else{
        $status = WebhookCron::create(array('cron_time'=>$hours));
        if($status){
            return 1;
        }else{
            return 0;
        }
      }
    }

     public function DeleteEmailLogs_invalid_email(Request $request){
         
        $status = InvalidEmail::whereDate('created_at','=',$request->date)->delete();
        if($status){
            return 1;
        }else{
            return 0;
        }
    }

    public function DeletelogsManaully(Request $request){
         if(File::exists(public_path('uploads/errorlogs.txt'))){
           //$file =  File::delete(public_path('uploads/errorlogs.txt'));
          $unlink = unlink(public_path('uploads/errorlogs.txt'));
           return $unlink;
        }else{
            return 0;
        }
    }
}
