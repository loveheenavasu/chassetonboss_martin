<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventTemplate;
use App\Models\GmailConnection;
use DateTime;

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
        return $parts[array_rand($parts)];
    }
}

class EventTemplateController extends Controller
{
    public function index()
    {
        return view('eventtemplate.index');
    }

    public function create()
    {
        return view('eventtemplate.create');
    }

    public function show($event_id)
    {
        $eventtemplate = EventTemplate::where('id',$event_id)->first();
     
        return view('eventtemplate.edit', compact('eventtemplate'));
    }

    public function contenttemplate()
    {
        $contentId = $_GET['contentId'];
        $spintax = new Spintax();
        
        $content= EventTemplate::where('id',$contentId)->first();
        $event_name = $spintax->process($content->event_name);
        $string =$content->spin_text;
        $result = $spintax->process($string);
        return ['result' => $result,'event_name' => $event_name];
    }

    public function sendTestTemplate()
    {
        $contentId = $_GET['contentId'];
        $spintax = new Spintax();
        $date = new DateTime();
        $timeZone = $date->getTimezone();
        $timezone = $timeZone->getName();
        $content = EventTemplate::where('id',$contentId)->first();
        $event_name = $spintax->process($content->event_name);
        $string = $content->spin_text;
        $event_content = $spintax->process($string);
        $allemail[]['email'] = $_GET['email'];
        $event_datetime = date('Y-m-d').'T'.date('H:i');

        $emailArray = array(
                      'summary' => $event_name,
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
        $allConnections = GmailConnection::whereNotNull('token')->get();

        if(!empty($allConnections)){
            foreach($allConnections as $count => $allConnection){
                $token[$count] = json_decode($allConnection->token);
                //$access_token = $token[0]->access_token;
                $access_token  =  $token[$count]->access_token;
                $email_id = $allConnection->email_id;


                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=".$access_token);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);
                if(isset($output->error) && $output->error == 'invalid_token'){
                    $status = 'error';
                    $code = 422;
                    $msg= 'Something went wrong';
                    //continue loop
                    continue;
                    return response()->json(['message' => $msg, 'code' => $code,'status'=> $status], $code, [ 'code' => $code,'status'=> $status]);
                }else{
                    
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
                        $status = 'success';
                        $code = 200;
                        $msg= 'Test template send sucessfully!';
                        echo response()->json(['message' => $msg, 'code' => $code,'status'=> $status], $code, [ 'code' => $code,'status'=> $status]);
                        break;
                    }else{
                        $status = 'error';
                        $code = 422;
                        $msg= 'Something went wrong';
                        
                        continue;
                        return response()->json(['message' => $msg, 'code' => $code,'status'=> $status], $code, [ 'code' => $code,'status'=> $status]);
                    }
                }
            }
        }
    }
}
