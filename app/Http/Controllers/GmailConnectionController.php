<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GmailConnection;
use App\Models\Groups;
use App\Models\Listing;
use App\Models\ProjectListing;
use DB;
use DataTables;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Google_Service_Analytics;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Calendar_EventAttendee;
use Google_Auth_AssertionCredentials;
use File;
use Google_Event;
use Session;


class GmailConnectionController extends Controller
{
    public Groups $group;
    
    public function index(Request $request)
    {   
        
        $session = session()->get('connection_id');
        if(isset($_GET['code'])){
            $data=GmailConnection::where('id',$session)->get()->toArray();
            $jsondata=ProjectListing::where('id',$data[0]['project_listing_id'])->get()->toArray();
            $file =  str_replace(' ','', $jsondata[0]['project_json']);
            $file = strtolower($file);
            $credentials = public_path('/'.$file);
            $client = new Google_Client();
            $client->setApplicationName('Calendar API Test');
            $client->setScopes( [
                                'https://www.googleapis.com/auth/calendar',
                                ] );
            $client->setAuthConfig($credentials);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                }else {
                    $authUrl = $client->createAuthUrl();
                    $authCode = $_GET['code'];
                    $accessToken = $client->fetchAccessTokenWithAuthCode($_GET['code']);
                    $client->setAccessToken($accessToken);
                    if (array_key_exists('error', $accessToken)) {
                        throw new Exception(join(', ', $accessToken));
                    }
                }
            }
            $getAccessToken = json_encode($client->getAccessToken());
            $saveToken = array('token' => $getAccessToken );
            GmailConnection::where('id',$session)
                            ->update($saveToken);

        }
        if ($request->ajax())
        {
           $searchvalue = $request->search['value'];
           $val = explode('/',$searchvalue);
           if(isset($val[3])){
            $value = $val[3];
           }else{
            $value = $val[0];
           }
            $allGroupInfos = GmailConnection::take(350)->get();
            
            return Datatables::of($allGroupInfos)
                ->addColumn('select', function ($row) {
                    return '';
                })
                ->addColumn('id', function ($row) {
                    return  $row->id;
                })
                ->addColumn('email_id', function ($row) {
                    return $row->email_id;
                })
                ->addColumn('group_name', function ($row) {
                    return $row->group_name;
                })
                ->addColumn('action', function ($row) {
                    
                    $btn = '';
                    if(isset($row->token)){
                        
                    $response = null;
                    if(!empty($row->token)){
                        $settings  = json_decode($row->token);
                        $response = $this->checkToken($settings->access_token);
                    }
                    
                    if(isset($response->error) && $response->error == 'invalid_token'){
                        $btn .= '<button class="open btn btn-secondary  btn-sm btn-secondary" id="testconnection" data-remote="'.$row->id.'" onclick="testConnection('.$row->id.')" >Re Authenticated</button>';
                    }else{
                        $btn .= '<button class="open btn btn-primary  btn-sm btn-primary disabled">Authenticated </button>';
                    }
                        
                        $btn .= '<button class="open btn btn-success  btn-sm btn-success" id="refreshToken" data-remote="'.$row->id.'" onclick="refreshToken('.$row->id.')" >Refresh Token</button>';
                    }else{
                         $btn .=  '<button class="open btn btn-light btn-sm" id="testconnection" data-remote="'.$row->id.'" onclick="testConnection('.$row->id.')" style="border-radius: 4px;border: 2px solid #cdc7c7f5;"><img width="20px" style="margin-bottom:3px; margin-right:10px;display: unset;" alt="Google sign-in" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Google_%22G%22_Logo.svg/512px-Google_%22G%22_Logo.svg.png" />Sign in with google</button>';
                    }
                    $btn .= '<a href="'.route('gmailconnection.edit', ['gmailconnection' => $row->id]).'" class="open btn btn-info btn-sm">Details</a>';
                    $btn .= '<button class="open btn btn-danger  btn-sm btn-delete" id="datatable-page" data-remote="'.$row->id.'" >Delete</a>';
                    return $btn;
                })
               ->addIndexColumn()
               ->make(true);
        }
        return view('gmailconnection.index');
    }

    public function create()
    {
        return view('gmailconnection.create');
    }

    public function edit(GmailConnection $gmailconnection)
    {
        
        return view('gmailconnection.edit', compact('gmailconnection'));
    }
    public function import()
    {
        return view('gmailconnection.connection-import');
    }
    public function getcheckedvalue(){
        $getselectedvalue=$_GET['getselectedvalue'];
        $getGroupId = $_GET['getGroupId'];
        $gmailids = explode(',',$getselectedvalue);
        foreach ($gmailids as $key => $value) {
            $checkExist = DB::table('gmail_connection_groups')
                                    ->where('groups_id',$getGroupId)
                                    ->where('gmail_connection_id',$value)
                                    ->first();
            if(empty($checkExist)){
                DB::table('gmail_connection_groups')->insert(
                     array(
                            'groups_id'     =>   $getGroupId, 
                            'gmail_connection_id'   =>   $value
                     )
                );
            }

            $gmailConn = DB::table('gmail_connections')
                                    ->where('group_id',$getGroupId)
                                    ->where('id',$value)
                                    ->first();
            if(empty($gmailConn)){
                DB::table('gmail_connections')
                ->where('id', $value)
                ->update(array('group_id' => $getGroupId));
            }
        }
       
    }
    public function getproxycheckedvalue(){
        $getselectedvalue=$_GET['getselectedvalue'];
        $getProxyId = $_GET['getProxyId'];
        $gmailids = explode(',',$getselectedvalue);
        foreach ($gmailids as $key => $value) {
            $group = array('proxy' => $getProxyId);
            GmailConnection::where('id',$value)->update($group);
        }

    }
    public function deletegmailconnection(GmailConnection $gmailconnection){
        $gmailId= $_GET['id'];
        $result = GmailConnection::where('id', $gmailId)->delete();
        if($result){
            return true;
        }else{
            return false;
        }
    }
    public function testconnection(GmailConnection $connection){
        $gmailId= $_GET['id'];
        $data=GmailConnection::where('id',$gmailId)->get()->toArray();
        $jsondata=ProjectListing::where('id',$data[0]['project_listing_id'])->get()->toArray();
        $file =  str_replace(' ','', $jsondata[0]['project_json']);
        $file = strtolower($file);
        $credentials = json_decode(file_get_contents($file),true);
        $auth_uri = $credentials['web']['auth_uri'];
        $client_id = $credentials['web']['client_id'];
        $client_secret = $credentials['web']['client_secret'];
        $scope = 'https://www.googleapis.com/auth/calendar';
        $redirect_uri = $credentials['web']['redirect_uris'][0];
        $redirect = $auth_uri.'?response_type=code&access_type=offline&client_id='.$client_id.'&redirect_uri='.$redirect_uri.'&state&scope='.$scope.'&prompt=select_account consent';
        if(session()->has('connection_id')){
            session()->forget('connection_id');
            session()->put('connection_id', $gmailId); 
        }else{
            session()->put('connection_id', $gmailId);
        }
        print_r($redirect);
        
    }
    public function refreshtoken(GmailConnection $connection){
        $gmailId= $_GET['id'];
        $data = GmailConnection::where('id',$gmailId)->get()->toArray();
        $jsondata = ProjectListing::where('id',$data[0]['project_listing_id'])->get()->      toArray();
        $file =  str_replace(' ','', $jsondata[0]['project_json']);
        $file = strtolower($file);
        $credentials = public_path('/'.$file);
        $client = new Google_Client();
        $client->setApplicationName('Calendar API Test');
        $client->setScopes( [
                            'https://www.googleapis.com/auth/calendar',
                            ] );
        $client->setAuthConfig($credentials);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $checkToken = GmailConnection::where('id',$gmailId)->whereRaw('token')->first();
        if(!empty($checkToken)){
            $accessToken = json_decode($checkToken->token, true);
            $client->setAccessToken($accessToken);
            if ($client->isAccessTokenExpired()) {
                if ($client->getRefreshToken()) {
                    $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                }
            }
            $getAccessToken = json_encode($client->getAccessToken());
            $saveToken = array('token' => $getAccessToken );
            $result = GmailConnection::where('id',$gmailId)
                            ->update($saveToken);
            if($result){
                return 'Token Updated Sucessfully!';
            }else{
                return 'Something Went Wrong!';
            }
        }
    }
    public function checkToken($token){

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=".$token);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output);
    }
}
