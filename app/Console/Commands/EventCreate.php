<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\ServiceProvider;
use Livewire\Component;
use App\Actions\SendWebhookDummyData;
use App\Actions\TestConnection;
use App\Models\Groups;
use App\Models\EventTemplate;
use App\Models\GmailConnection;
use App\Models\Event;
use App\Models\EventEmail;
use App\Models\EmailLogs;
use App\Models\EventEmailLogs;
use App\Models\EventInvalidEmail;
use App\Models\EventListingEmail;
use App\Models\RuleAction;
use App\Models\GmailConnectionGroup;
use DB;
use App\Models\Email;
use App\Tools;
use Livewire\WithPagination;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
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


class Spintax
{
    public function process($text)
    {
        return preg_replace_callback(
            '/\{(((?>[^\{\}]+)|(?R))*?)\}/x',
            array($this, 'replace'),
            $text
        );
    }
    public function replace($text)
    {
        $text = $this->process($text[1]);
        $parts = explode('|', $text);
        shuffle($parts);
        return $parts[array_rand($parts)];
    }
}

class EventCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:create {event_id}';

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

   public function GetAllEmails($eventId,$event_name,$timezone,$email_count,$gmail_id){
        $allemail = [];
        $allEmailsArray =[];
        $all_lists = DB::table('event_event_listing')
                    ->leftjoin('event as e','e.id','=','event_event_listing.event_id')
                    ->where('event_id',$eventId)
                    ->pluck('event_listing_id')
                    ->toArray();

        $onlyOneEmail = $email_count;
        $allEmailsInfos = EventListingEmail::whereIn('event_listing_id',$all_lists)
                ->join('eventemails as e','e.id','=','eventlisting_emails.event_email_id')
                ->leftjoin('eventemails_infos as ef','ef.event_email_id','=','e.id')
                ->where('e.sync_status','no')
                ->select('e.id as email_id','e.email as email','ef.value','ef.type as type','event_listing_id as listing_id','eventlisting_emails.event_email_id as ee_id')
                ->orderBy(DB::raw('RAND()'))
                ->limit(1)->get();
        foreach ($allEmailsInfos as $key => $allEmailsInfo) {
            $curl = curl_init();
            curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key=private_a858390e9dc3175c6e809053edc7349f&email='.$allEmailsInfo['email'].' ',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            ));
            $response = curl_exec($curl);
            curl_close($curl);
            
            $validation_check = json_decode($response);
            if($validation_check->result == 'invalid'){
                $invalid_emails = array('email'=>$allEmailsInfo['email'],'status'=>$validation_check->result,'type' => $gmail_id,'event_id' => $eventId,'event_name' => $event_name,'timezone' => $timezone);
                
                $InvalidEmail = EventInvalidEmail::create($invalid_emails);

                $sync_status = array('sync_status'=>'yes');
                EventEmail::where('email',$allEmailsInfo['email'])
                                        ->update($sync_status);
                $listemail_status = array('in_pool'=>0);
                DB::table('eventlisting_emails')
                        ->where('event_email_id',$allEmailsInfo['ee_id'])
                        ->update($listemail_status);

            }else{
               $allemail[]['email'] = $allEmailsInfo['email'];
               $allEmailsArray[$key]['email'] =  $allEmailsInfo['email'];  
               $allEmailsArray[$key]['ee_id'] =  $allEmailsInfo['ee_id'];  
            }  
        }
        return ['allemail' => $allemail,'allEmailsArray' => $allEmailsArray];
    }

    public function GetAllGroups($eventId){
        $all_connections = DB::table('gmail_connection_groups')
                    ->leftjoin('gmail_connections as g','g.id','=','gmail_connection_groups.gmail_connection_id')
                    ->leftjoin('event as ev','ev.id','=','gmail_connection_groups.event_id')
                    ->where('gmail_connection_groups.sync_status','no')
                    ->where('event_id',$eventId)
                    ->inRandomOrder()
                    ->first();
        return $all_connections;
    }

    public function TotalAllGroupsSync($eventId){
        $all_connections = DB::table('gmail_connection_groups')
                    ->leftjoin('gmail_connections as g','g.id','=','gmail_connection_groups.gmail_connection_id')
                    ->leftjoin('event as ev','ev.id','=','gmail_connection_groups.event_id')
                    ->where('gmail_connection_groups.sync_status','yes')
                    ->where('event_id',$eventId)
                    ->get();
        return $all_connections;
    }

    public function TotalAllGroupsWithoutSync($eventId){
        $all_connections = DB::table('gmail_connection_groups')
                    ->leftjoin('gmail_connections as g','g.id','=','gmail_connection_groups.gmail_connection_id')
                    ->leftjoin('event as ev','ev.id','=','gmail_connection_groups.event_id')
                    ->where('event_id',$eventId)
                    ->get();
        return $all_connections;
    }

    public function roundToNearestInterval($timestamp){
        $timestamp += 60 * 30;
        list($m, $d, $y, $h, $i, $s) = explode(' ', date('m d Y H i s', $timestamp));
        if ($s != 0) $s = 0;

        if ($i < 15) {
            $i = 15;
        } else if ($i < 30) {
            $i = 30;
        } else if ($i < 45) {
            $i = 45;
        } else if ($i < 60) {
            $i = 0;
            $h++;
        }
        return mktime($h, $i, $s, $m, $d, $y);
    }
    
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        $group = $this->argument('event_id');
        $allRules = Event::where('id',$group)->get();
        $allemail = [];
        if(!empty($allRules)){
            foreach ($allRules as $key => $allRule) {
                if($allRule->status == 'running'){
                    // Connnection type groups
                    if($allRule->connection_type == 0){
                        // Get all event content data start
                        $spintax = new Spintax();
                        $timezone = $allRule->timezone; 
                        $event_name = $allRule->name; 
                        $template_id = $allRule->template_id; 
                        $templatesData = EventTemplate::where('id',$template_id)->first();
                        $email_count = $allRule->emails_count;
                        $temp_event_name = $spintax->process($templatesData->event_name);
                        $event_spintext = $templatesData->spin_text;
                        $event_content = $spintax->process($event_spintext);
                        $event_location = $templatesData->event_location;
                        // Get all event content data end

                        // Update sync gmail connection start

                        // $allGroupwithSync = $this->TotalAllGroupsSync($group);
                        // $allGroupwithoutSync = $this->TotalAllGroupsWithoutSync($group);
                        // $group_main_group_id = $allGroupwithoutSync[0]->groups_id;
                        // $group_event_id = $allGroupwithoutSync[0]->event_id;
                        // if(count($allGroupwithSync) == count($allGroupwithoutSync)){
                        //     $group_sync = array('sync_status'=>'no');
                        //     GmailConnectionGroup::where('event_id',$group_event_id)
                        //                 ->update($group_sync);
                        // }

                        // Update sunc gamil connection end

                        // Get all groups from multiple list start
                        $all_connections = $this->GetAllGroups($group);
                        $gmail_id = $all_connections->email_id;
                        // Get all groups from multiple list end

                        // $email_sync_valid = EventEmailLogs::where(
                        //                             [ 
                        //                                 ['type', '=',$gmail_id],
                        //                                 ['event_id', '=',$all_connections->event_id]
                        //                             ]
                        //                         )->get()->count();
                        // $email_sync_invalid = EventInvalidEmail::where(
                        //                             [ 
                        //                                 ['type', '=',$gmail_id],
                        //                                 ['event_id', '=',$all_connections->event_id]
                        //                             ]
                        //                         )->get()->count();
                        // $total_sync_email = $email_sync_valid+$email_sync_invalid-1;
                        // if($total_sync_email == $email_count){
                        //     $connectionSync = array('sync_status'=>'yes');
                        //     DB::table('gmail_connection_groups')
                        //             ->where(
                        //                     [ 
                        //                         ['gmail_connection_id', '=',$all_connections->gmail_connection_id],
                        //                         ['event_id', '=',$all_connections->event_id],
                        //                         ['groups_id', '=', $all_connections->groups_id] 
                        //                     ]
                        //                 )
                        //                 ->update($connectionSync);
                        // }
                        
                        // Get all emails from multiple list start
                        $allEmailArray = $this->GetAllEmails($group,$event_name,$timezone,$email_count,$gmail_id);
                        $allemail = $allEmailArray['allemail'];
                        $allEmailsArray = $allEmailArray['allEmailsArray'];
                        // Get all emails from multiple list end

                        // Event Create Code Start
                        $groups_id = $all_connections->groups_id; 
                        $event_id = $all_connections->event_id;
                        $event_name = $all_connections->name;
                        $event_timezone = $all_connections->timezone;
                        $gmail_connection_id = $all_connections->gmail_connection_id; 
                        $token[] = json_decode($all_connections->token);
                        $access_token = $token[0]->access_token;
                        $email_id = $all_connections->email_id;
                        $startTime = date('H:i', $this->roundToNearestInterval(strtotime(date('H:i'))));
                        $new_Time = $startTime;
                        $event_datetime = date('Y-m-d').'T'.$new_Time;
                        $emailArray = array(
                          'summary' => $temp_event_name,
                          'location' => $event_location,
                          'description' => $event_content,
                          'start' => array(
                            'dateTime' => $event_datetime.':00-00:00',
                            'timeZone' => $timezone,
                          ),
                          'end' => array(
                            'dateTime' => $event_datetime.':00-00:00',
                            'timeZone' => $timezone,
                          ),
                          'attendees' => $allemail,
                          'guestsCanSeeOtherGuests' => false
                        );
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://www.googleapis.com/calendar/v3/calendars/'.$email_id.'/events?sendNotifications=true&supportsAttachments=true',
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_POSTFIELDS =>json_encode($emailArray),
                          CURLOPT_HTTPHEADER => array(
                            'Authorization: Bearer '.$access_token,
                            'Content-Type: application/json'
                          ),
                        ));
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $response = json_decode($response);
                        if(isset($response->htmlLink)){
                            foreach($allEmailsArray as $res){
                                $valid_emails=array('email'=>$res['email'],'status'=>'valid','type' => $email_id,'event_id' => $event_id,'event_name' => $event_name,'timezone' => $event_timezone);
                                $validEmail = EventEmailLogs::create($valid_emails);

                                $sync_status = array('sync_status'=>'yes');
                                EventEmail::where('email',$res['email'])
                                ->update($sync_status);

                                $listemail_status = array('in_pool'=>0);
                                DB::table('eventlisting_emails')
                                        ->where('event_email_id',$res['ee_id'])
                                        ->update($listemail_status);
                               
                            }
                            echo "Event created sucessfully!";
                        }else{
                            echo "Something went wrong!";
                        }
                        // Event Create Code End
                    }else{
                        // Get all event content data start
                        $spintax = new Spintax();
                        $connection_id = $allRule->connection_id; 
                        $event_name = $allRule->event_name; 
                        $timezone = $allRule->timezone;
                        $gmailconnection = GmailConnection::where('id',$connection_id)->first();
                        $token[] = json_decode($gmailconnection->token);
                        $access_token = $token[0]->access_token; 
                        $email_id = $gmailconnection->email_id;
                        $email_count = $allRule->emails_count; 
                        $template_id = $allRule->template_id;
                        $templatesData = EventTemplate::where('id',$template_id)->first();
                        $temp_event_name = $spintax->process($templatesData->event_name);
                        $event_spintext = $templatesData->spin_text;
                        $event_content  = $spintax->process($event_spintext);
                        $event_location = $templatesData->event_location;
                        // Get all event content data end

                        
                        // Get all emails from multiple list start
                        $allEmailArray = $this->GetAllEmails($group,$event_name,$timezone,$email_count);
                        $allemail = $allEmailArray['allemail'];
                        $allEmailsArray = $allEmailArray['allEmailsArray'];
                        // Get all emails from multiple list end

                        $all_connections = DB::table('gmail_connections')
                                        ->leftjoin('event as ev','ev.connection_id','=','gmail_connections.id')
                                        ->where('gmail_connections.id',$connection_id)
                                        ->get();
                        foreach($all_connections as $key => $result){
                            $event_id = $result->id;
                            $event_name = $result->name;
                            $event_timezone = $result->timezone;
                            $gmail_connection_id = $result->connection_id; 
                            $token[] = json_decode($result->token);
                            $access_token = $token[0]->access_token;
                            $email_id = $result->email_id;
                            $startTime = date('H:i', $this->roundToNearestInterval(strtotime(date('H:i'))));
                            $new_Time = $startTime;
                            $event_datetime = date('Y-m-d').'T'.$new_Time;
                            $emailArray = array(
                              'summary' => $temp_event_name,
                              'location' => $event_location,
                              'description' => $event_content,
                              'start' => array(
                                'dateTime' => $event_datetime.':00-00:00',
                                'timeZone' => $timezone,
                              ),
                              'end' => array(
                                'dateTime' => $event_datetime.':00-00:00',
                                'timeZone' => $timezone,
                              ),
                              'attendees' => $allemail,
                              'guestsCanSeeOtherGuests' => false
                            );
                            $curl = curl_init();
                            curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://www.googleapis.com/calendar/v3/calendars/'.$email_id.'/events?sendNotifications=true&supportsAttachments=true',
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => '',
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 0,
                              CURLOPT_FOLLOWLOCATION => true,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => 'POST',
                              CURLOPT_POSTFIELDS =>json_encode($emailArray),
                              CURLOPT_HTTPHEADER => array(
                                'Authorization: Bearer '.$access_token,
                                'Content-Type: application/json'
                              ),
                            ));
                            $response = curl_exec($curl);
                            curl_close($curl);
                            $response = json_decode($response);
                            if(isset($response->htmlLink)){
                               foreach($allEmailsArray as $res){
                                    $valid_emails=array('email'=>$res['email'],'status'=>'valid','type' => $email_id,'event_id' => $event_id,'event_name' => $event_name,'timezone' => $event_timezone);
                                    $validEmail = EventEmailLogs::create($valid_emails);

                                    $sync_status = array('sync_status'=>'yes');
                                    EventEmail::where('email',$res['email'])
                                    ->update($sync_status);

                                    $listemail_status = array('in_pool'=>0);
                                    DB::table('eventlisting_emails')
                                        ->where('event_email_id',$res['ee_id'])
                                        ->update($listemail_status);
                                    echo "Event created sucessfully!";
                                }
                            }else{
                                echo "Something went wrong!";
                            }
                        }
                    }
                }
            }
        }
    }
}
