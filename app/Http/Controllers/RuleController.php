<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Rule;
use App\Models\EmailLogs;
use App\Models\InvalidEmail;
use App\Models\Connection;
use DataTables;

class RuleController extends Controller
{
    public function index()
    {
       
        return view('rules.index');
    }

    public function create()
    {
        return view('rules.create');
    }

    public function show(Rule $rule)
    {
        return view('rules.show', compact('rule'));
    }

    public function mauticStages(Request $request){
       $id = $request->data;
       $slected = $request->slected;
       $apiData = Connection::where('id',$id)->first()->toArray();
       $login = $apiData['username'];
       $password = $apiData['password'];
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $apiData['base_url'].'/api/stages?limit=500',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'Cache-Control: no-cache',
            'Content-Type: application/x-www-form-urlencoded',
            'token: a424d49046a2cc1acf014ebaff5987964332ef85',
            'Authorization: Basic '.base64_encode($login . ":" . $password)
          ),
        ));

        $response = curl_exec($curl);   
        curl_close($curl);
        $allstages = json_decode($response);
        $results = $allstages->stages;
        $final_array = '';
        $selectedVal = $slected;

        $final_array .=  '<option value=""></option>';
        foreach ($results as $key => $result) {
          if($result->id == $selectedVal ){
            $selected = 'selected';
           }else{
            $selected = '';
           }
           $final_array .= '<option value="'.$result->id.'" >'.$result->name.'</option>';
          }
        
        return $final_array;
    }

    public function emailSyncedList(Request $request){
       if ($request->ajax())
        {
           $rule_id = $request->rule_id;
            $emails = EmailLogs::
            where('rule_number',$rule_id)
            ->select('email')->get()->toArray();

            $emails2 = InvalidEmail::
            where('rule_number',$rule_id)
            ->select('email')->get()->toArray();
            $final = array_merge($emails,$emails2);

           // echo '<pre>';print_r($final); die;
            return Datatables::of($final)
                ->addColumn('id', function ($row) {
                    return $row['email'];
                })
                ->addColumn('email', function ($row) {
                    return $row['email'];
                })
               
               ->make(true);
        }
    }

    
    public function deleteCheckedRule(Request $request)
    {
        $ids = $request->ids;
        $result = Rule::whereIn('id',explode(',',$ids))->delete();
        if($result){
            echo "true";
        }
        else{
            echo "false";
        }
    }
}
