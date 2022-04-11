<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Http\Request;
use App\Actions\SendWebhookDummyData;
use App\Actions\TestConnection;
use App\Models\GmailConnection;
use App\Models\ProjectListing;
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

class GooglerefreshToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'googlerefresh:Token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $checkToken = GmailConnection::whereRaw('token')->get()->toArray();
        if(!empty($checkToken)){
            foreach($checkToken as $reftoken){
                $jsondata=ProjectListing::where('id',$reftoken['project_listing_id'])->get()->toArray();
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
                $accessToken = json_decode($reftoken['token'], true);
                $client->setAccessToken($accessToken);

                if ($client->isAccessTokenExpired()) {
                    if ($client->getRefreshToken()) {
                        $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                    }
                }
                $getAccessToken = json_encode($client->getAccessToken());
                $saveToken = array('token' => $getAccessToken );
                $res = GmailConnection::where('id',$reftoken['id'])
                                ->update($saveToken);
            }
        }
    }
}
