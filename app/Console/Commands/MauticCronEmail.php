<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rule;
use Livewire\Component;
use App\Models\ListingEmail;
use App\Models\MauticLogs;
use App\Models\Email;
use App\Models\InvalidEmail;
use App\Models\EmailLogs;
use App\Models\Connection;
use App\Models\RuleAction;
use App\Models\Listing;
use Faker\Generator;
use App\Models\EmailInfo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithPagination;
use App\Mail\MailtrapExample;
use Illuminate\Support\Facades\Mail;

class MauticCronEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mauticemail:cron {rule_id}';

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


    public function isSiteAvailible($checkUrl){
      // Check, if a valid url is provided
      if(!filter_var($checkUrl, FILTER_VALIDATE_URL)){
          return false;
      }

      // Initialize cURL
      $curlInit = curl_init($checkUrl);
      
      // Set options
      curl_setopt($curlInit,CURLOPT_CONNECTTIMEOUT,10);
      curl_setopt($curlInit,CURLOPT_HEADER,true);
      curl_setopt($curlInit,CURLOPT_NOBODY,true);
      curl_setopt($curlInit,CURLOPT_RETURNTRANSFER,true);

      // Get response
      $response = curl_exec($curlInit);
      
      // Close a cURL session
      curl_close($curlInit);

      return $response?true:false;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
      $group = $this->argument('rule_id');
      $allRules = Rule::where('id',$group)->get();
      $valid_count = '';
      $neverBounce_key = env('NEVERBOUNCE_API_KEY');
      if(!empty($allRules)){
        
        foreach ($allRules as $key => $allRule) {
          $checkUrl = Connection::where('id',$allRule->connection_id)->first()->base_url;
          if($this->isSiteAvailible($checkUrl)){
            if($allRule->status == 'running'){
              $webhook_id_selected = $allRule['webhook_id_selected'];
              if($webhook_id_selected){
                $webhook_id_selected_url = Connection::where('id',$webhook_id_selected)->first()->base_url;
              }else{
                $webhook_id_selected_url = '';
              }
              $connection_id = $allRule['connection_id'];
              $allConnection = Connection::where('id',$connection_id)->first();
              if($allConnection->type == 'mautic'){ 
                $type = 'mautic';
                $rule_id = $allRule['id'];
                $stage_id = $allRule['stage_id'];
                $emails_count = $allRule['emails_count']; 
                $mautic_url = $allConnection->base_url; 
                $login = $allConnection->username; 
                $password = $allConnection->password; 
                $allEmails = [];
                $valid_emails=[];
                $invalid_emails=[];
                $final_array = [];
                $all_lists = DB::table('listing_rule')
                                      ->leftjoin('rules as r','r.id','=','listing_rule.rule_id')
                                      ->where('rule_id',$rule_id)
                                      ->get();
                if(!empty($all_lists)){
                  foreach ($all_lists as $key => $all_list) {                  
                    $allEmailsInfos = ListingEmail::where('listing_id',$all_list->listing_id)
                      ->join('emails as e','e.id','=','listing_email.email_id')
                      ->leftjoin('email_infos as ef','ef.email_id','=','e.id')
                      ->where('e.sync_status','no')
                      ->select('e.id as email_id','e.email as email','ef.value','ef.type as type','listing_id as listing_id')->get()->toArray();
                    foreach ($allEmailsInfos as $allEmailsInfo) {
                      $final_array[$allEmailsInfo['email']][$allEmailsInfo['type']] = $allEmailsInfo['value'];
                      $final_array[$allEmailsInfo['email']]['rule_number'] = $all_list->rule_id;
                      $final_array[$allEmailsInfo['email']]['rule_name'] = $all_list->name;
                      $final_array[$allEmailsInfo['email']]['timezone'] = $all_list->timezone;
                      $final_array[$allEmailsInfo['email']]['listing_id'] = $allEmailsInfo['listing_id'];
                      $final_array[$allEmailsInfo['email']]['email_id'] = $allEmailsInfo['email_id'];
                    }
                    
                    $checkemail = 0;
                    $calEmail = 1;
                    $allEmailLogsArray = [];
                    $allInvalidEmailArray = [];
                    $allEmailLogsArray = EmailLogs::where('rule_number', $rule_id)->whereDate('created_at', Carbon::today())->get()->toArray();
                    $allInvalidEmailArray = InvalidEmail::where('rule_number', $rule_id)->whereDate('created_at', Carbon::today())->get()->toArray();
                    $get_allemail = array_merge($allEmailLogsArray,$allInvalidEmailArray);
                    $allSyncMail = count($get_allemail);
                    $checkActionExist = RuleAction::where('rule_id',$rule_id)->whereDate('created_at', Carbon::today())->get()->first();
                    $actionStatus = array('rule_id'=>$rule_id,'emails_count' => $emails_count);
                    if(!empty($checkActionExist)){
                      RuleAction::where('id',$checkActionExist->id)->update($actionStatus);
                    }else{
                        if($allSyncMail == $emails_count){
                          RuleAction::create($actionStatus);
                        }
                    }
                    foreach ($final_array as $key => $value) {
                      if($checkemail  < $calEmail && $allSyncMail <= $emails_count){
                        $checkemail++;
                        $curl = curl_init();
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key='.$neverBounce_key.'&email='.$key.' ',
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
                        if(isset($validation_check) && $validation_check->result == 'invalid'){
                          $invalid_emails = array('email'=>$key,'status'=>$validation_check->result,'type' => $type,'rule_number' => $value['rule_number'],'rule_name' => $value['rule_name'],'timezone' => $value['timezone']);
                          $checkMail = InvalidEmail::where([
                                                          ['email', '=', $key],
                                                          ['type', '=', $type],
                                                          ['rule_number', '=', $value['rule_number']]
                                                          ])->first();
                          $listemail_status = array('in_pool'=>0);
                          DB::table('listing_email')
                              ->where('email_id',$value['email_id'])
                              ->update($listemail_status);

                          if(empty($checkMail)){
                                $InvalidEmail = InvalidEmail::create($invalid_emails);
                                $sync_status = array('sync_status'=>'yes');
                                  Email::where('email',$key)
                                    ->update($sync_status);
                            }else{
                                $InvalidEmail = InvalidEmail::where('id',$checkMail->id)->update($invalid_emails);
                                $sync_status = array('sync_status'=>'yes');
                                  Email::where('email',$key)
                                    ->update($sync_status);
                            }
                        }else{
                          $first_name = isset($value['first_name']) ? $value['first_name'] : '';
                          $last_name = isset($value['last_name']) ? $value['last_name'] : '';
                          $mobile = isset($value['mobile']) ? $value['mobile'] : '';
                          $phone = isset($value['phone']) ? $value['phone'] : '';
                          $address_line1 = isset($value['address_line1']) ? $value['address_line1'] : '';
                          $address_line2 = isset($value['address_line2']) ? $value['address_line2'] : '';
                          $city = isset($value['city']) ? $value['city'] : '';
                          $state = isset($value['state']) ? $value['state'] : '';
                          $zipcode = isset($value['zip_code']) ? $value['zip_code'] : '';
                          $country = isset($value['country']) ? $value['country'] : '';
                          $website = isset($value['website']) ? $value['website'] : '';
                          $facebook = isset($value['facebook']) ? $value['facebook'] : '';
                          $instagram = isset($value['instagram']) ? $value['instagram'] : '';
                          $googleplus = isset($value['google+']) ? $value['instagram'] : '';
                          $rule_number = isset($value['rule_number']) ? $value['rule_number'] : '';

                          $rule_name = isset($value['rule_name']) ? $value['rule_name'] : '';

                          $timezone = isset($value['timezone']) ? $value['timezone'] : '';
                          $listing_id = isset($value['listing_id']) ? $value['listing_id'] : '';
                          $email_id = isset($value['email_id']) ? $value['email_id'] : '';
                          $position = isset($value['position']) ? $value['position'] : '';
                          $company_name = isset($value['company_name']) ? $value['company_name'] : '';
                          $skype = isset($value['skype']) ? $value['skype'] : '';

                         $valid_emails[] = array('email'=>$key,'first_name' => $first_name,'last_name'=>$last_name,'mobile'=>$mobile,'phone'=>$phone,'address_line1'=>$address_line1,'address_line2'=>$address_line2,'city'=>$city,'state' => $state,'zipcode' => $zipcode,'country'=>$country,'website'=>$website,'facebook'=>$facebook,'instagram' => $instagram,'googleplus' => $googleplus,'rule_number' => $rule_number,'rule_name' => $rule_name,'timezone' => $timezone,'listing_id' =>$listing_id,'email_id' => $email_id,'position' => $position,'company_name' => $company_name,'skype' => $skype);
                        }
                      }
                    }
                    
                    foreach ($valid_emails as $value) {
                      $curl = curl_init();
                      curl_setopt_array($curl, array(
                        CURLOPT_URL => $mautic_url.'/api/contacts/new',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',

                        CURLOPT_POSTFIELDS => 'email="'.$value['email'].'"&firstname='.$value['first_name'].'&lastname='.$value['last_name'].'&mobile='.$value['mobile'].'&phone='.$value['phone'].'&address1='.$value['address_line1'].'&address2='.$value['address_line2'].'&city='.$value['city'].'&state='.$value['state'].'&zipcode='.$value['zipcode'].'&country='.$value['country'].'&website='.$value['website'].'&facebook='.urlencode($value['facebook']).'&instagram='.$value['instagram'].'&googleplus="'.$value['googleplus'].'"&position='.$value['position'].'&skype='.$value['skype'],
                        CURLOPT_HTTPHEADER => array(
                          "Authorization: Basic " . base64_encode($login . ":" . $password)
                        ),
                      ));
                      $response = curl_exec($curl);
                      curl_close($curl);
                      $results = json_decode($response);
                      if(isset($results->errors)){
                        $all_error = 'Email:'.$value['email'].' '.'Rule Number:'.$value['rule_number'].' Rule Name:'.$value['rule_name']. ' Message:'.$results->errors[0]->message;
                        Storage::disk('public')->append('errorlogs.txt', $all_error,null);

                        $invalid_emails = array('email' => $value['email'],'status' => 'error','type' => $type,'rule_number' => $value['rule_number'],'rule_name' => $value['rule_name'],'timezone' => $value['timezone']);
                        $InvalidEmail = InvalidEmail::create($invalid_emails);

                        $sync_status = array('sync_status'=>'yes');
                        Email::where('email',$value['email'])
                          ->update($sync_status);

                        die;
                      }

                      if($results->contact !=''){
                        $emailLogs = array('email' => $value['email'],'status' => 'sucesss','type' => $type,'rule_number' => $value['rule_number'],'rule_name' => $value['rule_name'],'timezone' => $value['timezone']);
                          EmailLogs::create($emailLogs);

                          $sync_status = array('sync_status'=>'yes');
                          Email::where('email',$value['email'])
                          ->update($sync_status);

                          $listemail_status = array('in_pool'=>0);
                          DB::table('listing_email')
                              ->where('email_id',$value['email_id'])
                              ->update($listemail_status);

                          $updateList = Listing::where('id',$all_list->listing_id)->first();

                          if(isset($updateList->valid_emails)){
                            $valid_count = $updateList->valid_emails+1;
                            $valid_array = array('valid_emails'=>$valid_count);
                            Listing::where('id',$all_list->listing_id)->update($valid_array);
                          }else{
                            $valid_count = 1;
                            $valid_array = array('valid_emails'=>$valid_count);
                            Listing::where('id',$all_list->listing_id)->update($valid_array);
                          }

                       }else{
                        $emailLogs = array('email' => $value['email'],'status' => 'error','type' => $type,'rule_number' => $value['rule_number'],'rule_name' => $value['rule_name'],'timezone' => $value['timezone']);
                        EmailLogs::create($emailLogs);

                        $listemail_status = array('in_pool'=>0);
                        DB::table('listing_email')
                            ->where('email_id',$value['email_id'])
                            ->update($listemail_status);

                        $updateList = Listing::where('id',$all_list->listing_id)->first();

                        if(isset($updateList->valid_emails)){
                          $valid_count = $updateList->valid_emails+1;
                          $valid_array = array('valid_emails'=>$valid_count);
                          Listing::where('id',$all_list->listing_id)->update($valid_array);
                        }else{
                          $valid_count = 1;
                          $valid_array = array('valid_emails'=>$valid_count);
                          Listing::where('id',$all_list->listing_id)->update($valid_array);
                        }

                      }
                     
                      $curl = curl_init();
                        curl_setopt_array($curl, array(
                          CURLOPT_URL => $mautic_url.'/api/stages/'.$stage_id.'/contact/'.$results->contact->id.'/add',
                          CURLOPT_RETURNTRANSFER => true,
                          CURLOPT_ENCODING => '',
                          CURLOPT_MAXREDIRS => 10,
                          CURLOPT_TIMEOUT => 0,
                          CURLOPT_FOLLOWLOCATION => true,
                          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                          CURLOPT_CUSTOMREQUEST => 'POST',
                          CURLOPT_HTTPHEADER => array(
                            "Authorization: Basic " . base64_encode($login . ":" . $password)
                          ),
                        ));
                        $response = curl_exec($curl);
                        curl_close($curl);
                        $result_final=json_decode($response,true);
                        if(!empty($result_final['errors'][0]))
                        {
                          $non_sync_emails = array('email' => $value['email'],'status' =>                 $result_final['errors'][0]['message']);
                          MauticLogs::create($non_sync_emails);
                          $testmail= Mail::raw('We can not assign stage to '.$value['email'].' having error "'.$result_final['errors'][0]['message'].'" on syncing email ', function ($message) 
                                    {
                                      $message->to('mebongue@hotmail.com')
                                        ->subject('Mautic');
                                    });
                          
                          }
                        ////////////June 9 code for send data to webhook if mautc selected//////
                    $checkemail = 0;
                    $calEmail = 1;
                    foreach ($valid_emails as $value) {
                    if($checkemail  < $calEmail ){
                          $checkemail++;
                      $firstname = []; $lastname = []; $posit =[]; $mob =[]; 
                      $pho =[]; $add_line1 =[];$add_line2 =[];$cit =[];$sta =[];
                      $zip_co =[]; $cont =[]; $web =[]; $fb=[]; $inst =[]; 
                      $google =[];$companyname =[]; $numberofemployees =[]; $ind =[];
                      $datecreated =[]; $annualrevenue =[];

                                
                              $first_name = isset($value['first_name']) ? $value['first_name'] : '';
                              $last_name = isset($value['last_name']) ? $value['last_name'] : '';
                              $mobile = isset($value['mobile']) ? $value['mobile'] : '';
                              $position = isset($value['position']) ? $value['position'] : '';
                              $phone = isset($value['phone']) ? $value['phone'] : '';
                              $address_line1 = isset($value['address_line1']) ? $value['address_line1'] : '';
                              $address_line2 = isset($value['address_line2']) ? $value['address_line2'] : '';
                              $city = isset($value['city']) ? $value['city'] : '';
                              $state = isset($value['state']) ? $value['state'] : '';
                              $zipcode = isset($value['zip_code']) ? $value['zip_code'] : '';
                              $country = isset($value['country']) ? $value['country'] : '';
                              $website = isset($value['website']) ? $value['website'] : '';
                              $facebook = isset($value['facebook']) ? $value['facebook'] : '';
                              $instagram = isset($value['instagram']) ? $value['instagram'] : '';
                              $googleplus = isset($value['google+']) ? $value['google+'] : '';
                              $company_name = isset($value['company_name']) ? $value['company_name'] : '';
                              $number_of_employees = isset($value['number_of_employees']) ? $value['number_of_employees'] : '';
                              $industry = isset($value['industry']) ? $value['industry'] : '';
                              $date_created = isset($value['date_created']) ? $value['date_created'] : '';
                              $annual_revenue = isset($value['annual_revenue']) ? $value['annual_revenue'] : '';

                              $rule_number = isset($value['rule_number']) ? $value['rule_number'] : '';

                              $rule_name = isset($value['rule_name']) ? $value['rule_name'] : '';

                              $timezone = isset($value['timezone']) ? $value['timezone'] : '';
                              $listing_id = isset($value['listing_id']) ? $value['listing_id'] : '';
                              $email_id = isset($value['email_id']) ? $value['email_id'] : '';
                              $final_input = array($firstname,$lastname,$position
                                ,$phone,$address_line1,$address_line2,$city,$state,$zipcode,$country,$website,$facebook,$instagram,$googleplus,$company_name,$number_of_employees,$industry,$date_created,$annual_revenue);
                      // $allInfoCombine = array_merge($firstname,$lastname,$posit,$mob,$pho,$add_line1,$add_line2,$cit,$sta,$zip_co,$cont,$web,$fb,$inst,$google,$companyname,$numberofemployees,$ind,$datecreated,$annualrevenue);
                      $emailArray['emails'] = array("email" => $value['email'], "infos" => $final_input); 
                      $json = json_encode($emailArray);
                    
                      if($webhook_id_selected_url != ''){
                        $headers = array('Accept: application/json', 'Content-Type: application/json');
                      $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $webhook_id_selected_url);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
                      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                      curl_setopt($ch, CURLOPT_POST, 1);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $json); 
                      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                      $output = curl_exec($ch);
                      curl_close($ch);
                      $results = json_decode($output);
                      if($results->status == 'success'){
                        $emailLogs = array('email' => $value['email'],'status' => 'sucesss','type' => 'webhook','rule_number' => $value['rule_number'],'rule_name' => $value['rule_name'],'timezone' => $value['timezone']);
                        EmailLogs::create($emailLogs);

                        $sync_status = array('sync_status'=>'yes');
                        Email::where('email',$value['email'])
                        ->update($sync_status);

                        $listemail_status = array('in_pool'=>0);
                        DB::table('listing_email')
                              ->where('email_id',$value['email_id'])
                              ->update($listemail_status);

                       }else{
                        $emailLogs = array('email' => $value['email'],'status' => 'error','type' => 'webhook','rule_number' => $value['rule_number'],'rule_name' => $value['rule_name'],'timezone' => $value['timezone']);
                        EmailLogs::create($emailLogs);
                        $listemail_status = array('in_pool'=>0);
                        DB::table('listing_email')
                              ->where('email_id',$value['email_id'])
                              ->update($listemail_status);

                        }
                      }
                    }
                  }
                    /////////////////
                    }
                  }
                }
              }else{
                $type = 'webhook';
                $url = $allConnection->base_url;
                $rule_id = $allRule['id'];
                $stage_id = $allRule['stage_id'];
                $emails_count = $allRule['emails_count'];
                $webhook_split = $allRule['webhook_split'];
                $emailtype = $allRule['emailtype'];
                $connection_id = $allRule['connection_id'];
                $all_lists = DB::table('listing_rule')
                              ->leftjoin('rules as r','r.id','=','listing_rule.rule_id')
                              ->where('rule_id',$rule_id)
                              ->get();
                $allEmails = [];
                $valid_emails=[];
                $invalid_emails=[];
                $final_array = [];
                if(!empty($all_lists)){
                  foreach ($all_lists as $key => $all_list) {
                    $allEmailsInfos = ListingEmail::where('listing_id',$all_list->listing_id)
                      ->join('emails as e','e.id','=','listing_email.email_id')
                      ->leftjoin('email_infos as ef','ef.email_id','=','e.id')
                      ->where('e.sync_status','no')
                      ->select('e.id as email_id','e.email as email','ef.value','ef.type as type','listing_id as listing_id')->get()->toArray();
                    foreach ($allEmailsInfos as $key => $allEmailsInfo) {
                      $final_array[$allEmailsInfo['email']][$allEmailsInfo['type']] = $allEmailsInfo['value'];
                      $final_array[$allEmailsInfo['email']]['rule_number'] = $all_list->rule_id;
                      $final_array[$allEmailsInfo['email']]['rule_name'] = $all_list->name;
                      $final_array[$allEmailsInfo['email']]['timezone'] = $all_list->timezone;
                      $final_array[$allEmailsInfo['email']]['listing_id'] = $allEmailsInfo['listing_id'];
                      $final_array[$allEmailsInfo['email']]['email_id'] = $allEmailsInfo['email_id'];
                    }

                    $allEmailLogsArray = [];
                    $allInvalidEmailArray = [];
                    $allEmailLogsArray = EmailLogs::where('rule_number', $rule_id)->whereDate('created_at', Carbon::today())->get()->toArray();
                    $allInvalidEmailArray = InvalidEmail::where('rule_number', $rule_id)->whereDate('created_at', Carbon::today())->get()->toArray();
                    $get_allemail = array_merge($allEmailLogsArray,$allInvalidEmailArray);

                    $allSyncMail = count($get_allemail);
                    $checkemail = 0;
                    $calEmail = 1;

                    $checkActionExist = RuleAction::where('rule_id',$rule_id)->whereDate('created_at', Carbon::today())->get()->first();
                    $actionStatus = array('rule_id'=>$rule_id,'emails_count' => $emails_count);
                    if(!empty($checkActionExist)){
                      RuleAction::where('id',$checkActionExist->id)->update($actionStatus);
                    }else{
                        if($allSyncMail == $emails_count){
                          RuleAction::create($actionStatus);
                        }
                    }
                    
                    foreach ($final_array as $key => $value) {
                        if($checkemail  < $calEmail && $allSyncMail < $emails_count){
                          $checkemail++;
                          $curl = curl_init();

                          curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.neverbounce.com/v4/single/check?key='.$neverBounce_key .'&email='.$key.' ',
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

                          if(isset($validation_check) && $validation_check->result == 'invalid'){
                             $invalid_emails = array('email'=>$key,'status'=>$validation_check->result,'type' => $type,'rule_number' => $value['rule_number'],'rule_name' => $value['rule_name'],'timezone' => $value['timezone']);
                              $checkMail = InvalidEmail::where([
                                                                ['email', '=', $key],
                                                                ['type', '=', $type],
                                                                ['rule_number', '=', $value['rule_number']]
                                                                ])->first();
                              $listemail_status = array('in_pool'=>0);
                              DB::table('listing_email')
                                    ->where('email_id',$value['email_id'])
                                    ->update($listemail_status);

                              if(empty($checkMail)){
                                  $InvalidEmail = InvalidEmail::create($invalid_emails);
                                  $sync_status = array('sync_status'=>'yes');
                                    Email::where('email',$key)
                                      ->update($sync_status);
                              }else{
                                  $InvalidEmail = InvalidEmail::where('id',$checkMail->id)->update($invalid_emails);
                                  $sync_status = array('sync_status'=>'yes');
                                    Email::where('email',$key)
                                      ->update($sync_status);
                              }
                          }else{
                              $first_name = isset($value['first_name']) ? $value['first_name'] : '';
                              $last_name = isset($value['last_name']) ? $value['last_name'] : '';
                              $mobile = isset($value['mobile']) ? $value['mobile'] : '';
                              $position = isset($value['position']) ? $value['position'] : '';
                              $phone = isset($value['phone']) ? $value['phone'] : '';
                              $address_line1 = isset($value['address_line1']) ? $value['address_line1'] : '';
                              $address_line2 = isset($value['address_line2']) ? $value['address_line2'] : '';
                              $city = isset($value['city']) ? $value['city'] : '';
                              $state = isset($value['state']) ? $value['state'] : '';
                              $zipcode = isset($value['zip_code']) ? $value['zip_code'] : '';
                              $country = isset($value['country']) ? $value['country'] : '';
                              $website = isset($value['website']) ? $value['website'] : '';
                              $facebook = isset($value['facebook']) ? $value['facebook'] : '';
                              $instagram = isset($value['instagram']) ? $value['instagram'] : '';
                              $googleplus = isset($value['google+']) ? $value['google+'] : '';
                              $company_name = isset($value['company_name']) ? $value['company_name'] : '';
                              $number_of_employees = isset($value['number_of_employees']) ? $value['number_of_employees'] : '';
                              $industry = isset($value['industry']) ? $value['industry'] : '';
                              $date_created = isset($value['date_created']) ? $value['date_created'] : '';
                              $annual_revenue = isset($value['annual_revenue']) ? $value['annual_revenue'] : '';

                              $rule_number = isset($value['rule_number']) ? $value['rule_number'] : '';

                              $rule_name = isset($value['rule_name']) ? $value['rule_name'] : '';

                              $timezone = isset($value['timezone']) ? $value['timezone'] : '';
                              $listing_id = isset($value['listing_id']) ? $value['listing_id'] : '';
                              $email_id = isset($value['email_id']) ? $value['email_id'] : '';
                             $valid_emails[] = array(
                                                  'email'       =>$key,
                                                  'first_name'  =>$first_name,
                                                  'last_name'   =>$last_name,
                                                  'position'    =>$position,
                                                  'mobile'      =>$mobile,
                                                  'phone'       =>$phone,
                                                  'address_line1'=>$address_line1,
                                                  'address_line2'=>$address_line2,
                                                  'city'        =>$city,
                                                  'state'       => $state,
                                                  'zipcode'     => $zipcode,
                                                  'country'     =>$country,
                                                  'website'     =>$website,
                                                  'facebook'    =>$facebook,
                                                  'instagram'   => $instagram,
                                                  'googleplus'  => $googleplus,
                                                  'company_name' =>$company_name,
                                                  'number_of_employees' => $number_of_employees,
                                                  'industry' => $industry,
                                                  'date_created' => $date_created,
                                                  'annual_revenue' => $annual_revenue,
                                                  'rule_number'    => $rule_number,
                                                  'rule_name'      => $rule_name,
                                                  'timezone'       => $timezone,
                                                  'listing_id'     => $listing_id,
                                                  'email_id'       => $email_id
                                                );
                          }
                        }
                    }
                    $resultwebhooks = DB::table('connection_rule')
                                        ->where('rule_id',$rule_id)
                                        ->join('connections','connections.id','=','connection_rule.connection_id')
                                        ->get();
                    if(count($resultwebhooks) > 0){
                      $webhook_ids = DB::table('connection_rule')
                                    ->where('rule_id',$rule_id)
                                    ->join('connections','connections.id','=','connection_rule.connection_id')
                                    ->select('base_url')->get()->toArray();
                    }else{
                      $webhook_ids = DB::table('connections')->where('id',$connection_id)->get()->toArray();
                    }
                    $weblloop =0;
                    $webhookCount = count($webhook_ids);
                    foreach ($valid_emails as $value) {
                      if($webhook_split == 1 && $emailtype == 'random'){
                        shuffle($webhook_ids);
                        $webhookurl = $webhook_ids[$weblloop]->base_url;
                      }else{
                        $webhookurl = $webhook_ids[$weblloop]->base_url;
                      }
                      $weblloop++;
                      if($weblloop == $webhookCount){
                        $weblloop = 0;
                      }
                      $firstname = []; $lastname = []; $posit =[]; $mob =[]; 
                      $pho =[]; $add_line1 =[];$add_line2 =[];$cit =[];$sta =[];
                      $zip_co =[]; $cont =[]; $web =[]; $fb=[]; $inst =[]; 
                      $google =[];$companyname =[]; $numberofemployees =[]; $ind =[];
                      $datecreated =[]; $annualrevenue =[];


                      $firstname['first_name'] = array('type' => 'First Name', 'value' =>$value['first_name']);
                      $lastname['last_name'] = array('type' => 'Last Name', 'value' =>$value['last_name']);
                      $posit['position'] = array('type' => 'Position', 'value' =>$value['position']);
                      $mob['mobile'] = array('type' => 'Mobile', 'value' => $value['mobile']);
                      $pho['phone'] = array('type' => 'Phone', 'value' =>$value['phone']);
                      $add_line1['address_line1'] = array('type' => 'Address Line 1', 'value' =>$value['address_line1']);
                      $add_line2['address_line2'] = array('type' => 'Address Line 2', 'value' =>$value['address_line2']);
                      $cit['city'] = array('type' => 'City', 'value' =>$value['city']);
                      $sta['state'] = array('type' => 'State', 'value' =>$value['state']);
                      $zip_co['zip_code'] = array('type' => 'Zip Code', 'value' =>$value['zipcode']);
                      $cont['country'] = array('type' => 'Country', 'value' =>$value['country']);
                      $web['website'] = array('type' => 'Website', 'value' =>$value['website']);
                      $fb['facebook'] = array('type' => 'Facebook', 'value' =>$value['facebook']);
                      $inst['instagram'] = array('type' => 'Instagram', 'value' =>$value['instagram']);
                      $google['google+'] = array('type' => 'Google+', 'value' =>$value['googleplus']);
                      $companyname['company_name'] = array('type' => 'Company Name', 'value' =>$value['company_name']);
                      $numberofemployees['number_of_employees'] = array('type' => 'Number of Employees', 'value' =>$value['number_of_employees']);
                      $ind['industry'] = array('type' => 'Industry', 'value' =>$value['industry']);
                      $datecreated['date_created'] = array('type' => 'Date created', 'value' =>$value['date_created']);
                      $annualrevenue['annual_revenue'] = array('type' => 'Annual Revenue', 'value' =>$value['annual_revenue']);
                      $allInfoCombine = array_merge($firstname,$lastname,$posit,$mob,$pho,$add_line1,$add_line2,$cit,$sta,$zip_co,$cont,$web,$fb,$inst,$google,$companyname,$numberofemployees,$ind,$datecreated,$annualrevenue);
                      $emailArray['emails'] = array("email" => $value['email'], "infos" => $allInfoCombine); 
                      $json = json_encode($emailArray);
                      $headers = array('Accept: application/json', 'Content-Type: application/json');
                      $ch = curl_init();
                      curl_setopt($ch, CURLOPT_URL, $webhookurl);
                      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                      curl_setopt($ch, CURLOPT_TIMEOUT, 10);  
                      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                      curl_setopt($ch, CURLOPT_POST, 1);
                      curl_setopt($ch, CURLOPT_POSTFIELDS, $json); 
                      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                      $output = curl_exec($ch);
                      curl_close($ch);
                      $results = json_decode($output);
                    
                      if($results->status == 'success'){
                        $emailLogs = array('email' => $value['email'],'status' => 'sucesss','type' => $type,'rule_number' => $value['rule_number'],'rule_name' => $value['rule_name'],'timezone' => $value['timezone']);
                        EmailLogs::create($emailLogs);

                        $sync_status = array('sync_status'=>'yes');
                        Email::where('email',$value['email'])
                        ->update($sync_status);

                        $listemail_status = array('in_pool'=>0);
                        DB::table('listing_email')
                              ->where('email_id',$value['email_id'])
                              ->update($listemail_status);

                        $updateList = Listing::where('id',$all_list->listing_id)->first();

                        if(isset($updateList->valid_emails)){
                          $valid_count = $updateList->valid_emails+1;
                          $valid_array = array('valid_emails'=>$valid_count);
                          Listing::where('id',$all_list->listing_id)->update($valid_array);
                        }else{
                          $valid_count = 1;
                          $valid_array = array('valid_emails'=>$valid_count);
                          Listing::where('id',$all_list->listing_id)->update($valid_array);
                        }

                       }else{
                        $emailLogs = array('email' => $value['email'],'status' => 'error','type' => $type,'rule_number' => $value['rule_number'],'rule_name' => $value['rule_name'],'timezone' => $value['timezone']);
                        EmailLogs::create($emailLogs);
                        $listemail_status = array('in_pool'=>0);
                        DB::table('listing_email')
                              ->where('email_id',$value['email_id'])
                              ->update($listemail_status);
                        $updateList = Listing::where('id',$all_list->listing_id)->first();

                        if(isset($updateList->valid_emails)){
                          $valid_count = $updateList->valid_emails+1;
                          $valid_array = array('valid_emails'=>$valid_count);
                          Listing::where('id',$all_list->listing_id)->update($valid_array);
                        }else{
                          $valid_count = 1;
                          $valid_array = array('valid_emails'=>$valid_count);
                          Listing::where('id',$all_list->listing_id)->update($valid_array);
                        }

                      }
                    }
                  }
                }
              }
            }
          }else{
            Mail::send('emails.email_template', ['site_url' => $checkUrl], function ($message){
                $message->to('mebongue@hotmail.com')
                        ->subject('Drip Feed Sync Error');
            });  
          }
        }
      }
    }
}
