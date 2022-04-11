<?php

namespace App\Http\Livewire;
use Illuminate\Http\Request;
use App\Actions\SendWebhookDummyData;
use App\Actions\TestConnection;
use App\Models\GmailConnection;
use App\Models\Groups;
use App\Models\Proxy;
use App\Tools;
use Livewire\Component;
use Livewire\WithPagination;
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

class GmailConnectionList extends Component
{
    public int $connection_id; 
    public GmailConnection $GmailConnection;
    public array $testedConnections = [];
    use WithPagination;
    public bool $confirmingGmailConnectionDeletion = false;
    public ?int $gmailconnectionIdBeingDeleted;
    public Groups $groups;

    public function getGroupsProperty(){
        return Groups::get();
    }
    public function getProxyProperty(){
        return Proxy::get();
    }
    public function getGmailConnectionsProperty()
    {

        $session = session()->get('connection_id');
        if(isset($_GET['code'])){
            $credentials = public_path('/'.'credentials.json');
            $client = new Google_Client();
            $client->setApplicationName('Calendar API Test');
            $client->setScopes( [
                                'https://www.googleapis.com/auth/calendar',
                                ] );
            $client->setAuthConfig($credentials);
            $client->setAccessType('offline');
            $client->setPrompt('select_account consent');
            $checkToken = GmailConnection::where('id',$session)->whereRaw('token')->first();
            if(!empty($checkToken)){
                $accessToken = json_decode($checkToken->token, true);
                $client->setAccessToken($accessToken);
            }else{
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
        }
        return GmailConnection::latest()->paginate(10);
    }
    public function confirmGmailConnectionDeletion($GmailId)
    {
        $this->confirmingGmailConnectionDeletion = true;
        $this->gmailconnectionIdBeingDeleted = $GmailId;
    }

    public function deleteGmailConnection()
    {
        
        try{
            GmailConnection::query()->findOrNew($this->gmailconnectionIdBeingDeleted)->delete();
            $this->confirmingGmailConnectionDeletion = false;
            
           }catch(\Exception $e){
             $this->confirmingGmailConnectionDeletion = false;
           }
    }

    public function testConnection(GmailConnection $connection){
        if(session()->has('connection_id')){
            session()->forget('connection_id');
            session()->put('connection_id', $connection->id);
        }else{
            session()->put('connection_id', $connection->id);
        }
    }

    public function refreshGoogleToken(GmailConnection $connection){
        $credentials = public_path('/'.'credentials.json');
        $client = new Google_Client();
        $client->setApplicationName('Calendar API Test');
        $client->setScopes( [
                            'https://www.googleapis.com/auth/calendar',
                            ] );
        $client->setAuthConfig($credentials);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $checkToken = GmailConnection::where('id',$connection->id)->whereRaw('token')->first();
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
            $res = GmailConnection::where('id',$connection->id)
                            ->update($saveToken);
        }
    }
}
