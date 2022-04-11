<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Rule;
use App\Models\Event;
use App\Models\WebhookCron;
use DB;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Token Refresh Start 
        $schedule->command('googlerefresh:Token')
                    ->hourly();
        // Token Refresh End


        // Event Cron Start
        $connection_count = '';
        $all_connections = [];
        $array = [];
        $allEvents = Event::get();
        foreach ($allEvents as $allEvent) {
            if($allEvent['status'] == 'running'){
                if($allEvent['schedule_time']=='random'){
                    $connection_type = $allEvent['connection_type'];
                    $weekDay = (int)$allEvent['schedule_weekday'];
                    $schedule_event = $allEvent['schedule'];
                    $monthDay = (int)$allEvent['schedule_monthday'];
                    $event_id = $allEvent['id']; 
                    $allGmailConnectionListCount = DB::table('gmail_connection_groups')->where('event_id',$event_id)->get()->count();
                    $allScheduleDays = $allEvent['schedule_days'];
                    $email_per_day = $allEvent['emails_count']*$allGmailConnectionListCount;

                    $todayDate = date('Y-m-d h:m:s');
                    $tomorrowDate = date('Y-m-d 00:00:00',strtotime('+1 day'));

                    $total_hours = round((strtotime($tomorrowDate) - strtotime($todayDate))/3600, 0);
                    $lefthour = 24 - $total_hours;
                    $array = array();
                    mt_srand($event_id);

                    while(sizeof($array) < $email_per_day){
                        $array_value = mt_rand(0,23).":".str_pad(mt_rand(0,59), 2, "0", STR_PAD_LEFT);
                        if(in_array($array_value,$array)){
                           $custom_added = mt_rand(0,23).":".str_pad(mt_rand(0,59), 2, "300", STR_PAD_LEFT);
                            $array[] = $custom_added;
                        }else{
                            $array[] = $array_value;
                        }
                    }

                    if($schedule_event == 'daily'){
                        foreach ($array as $value) {
                            $time_diff = $value;
                            foreach ($allScheduleDays as $allScheduleDay) {
                                switch ($allScheduleDay) {
                                    case '1':
                                        $schedule->command('event:create',[$event_id])
                                                  ->weeklyOn($allScheduleDay,$time_diff)
                                                  ->timezone($allEvent['timezone']);
                                    break;

                                    case '2':
                                        $schedule->command('event:create',[$event_id])
                                                  ->weeklyOn($allScheduleDay,$time_diff)
                                                  ->timezone($allEvent['timezone']);
                                    break;

                                    case '3':
                                        $schedule->command('event:create',[$event_id])
                                                  ->weeklyOn($allScheduleDay,$time_diff)
                                                  ->timezone($allEvent['timezone']);
                                    break;

                                    case '4':
                                        $schedule->command('event:create',[$event_id])
                                                  ->weeklyOn($allScheduleDay,$time_diff)
                                                  ->timezone($allEvent['timezone']);
                                    break;

                                    case '5':
                                        $schedule->command('event:create',[$event_id])
                                                  ->weeklyOn($allScheduleDay,$time_diff)
                                                  ->timezone($allEvent['timezone']);
                                    break;

                                    case '6':
                                        $schedule->command('event:create',[$event_id])
                                                  ->weeklyOn($allScheduleDay,$time_diff)
                                                  ->timezone($allEvent['timezone']);
                                    break;

                                    case '7':
                                        $schedule->command('event:create',[$event_id])
                                                  ->weeklyOn($allScheduleDay,$time_diff)
                                                  ->timezone($allEvent['timezone']);
                                    break;
                                }
                            }
                        }
                    }else if($schedule_event == 'weekly'){
                        foreach ($array as $value) {
                            $time_diff = $value;
                            $schedule->command('event:create',[$event_id])
                                    ->weeklyOn($weekDay,$time_diff)
                                    ->timezone($allEvent['timezone']);
                        }
                    }else{
                        foreach ($array as $value) {
                            $time_diff = $value;
                            $schedule->command('event:create',[$event_id])
                                    ->monthlyOn($monthDay,$time_diff)
                                    ->timezone($allEvent['timezone']);
                        }
                    }
                }else{
                    $connection_type = $allEvent['connection_type'];
                    $weekDay = (int)$allEvent['schedule_weekday'];
                    $schedule_event = $allEvent['schedule'];
                    $monthDay = (int)$allEvent['schedule_monthday'];
                     
                    $id = $allEvent['id']; 
                    $allGmailConnectionListCount = DB::table('gmail_connection_groups')->where('event_id',$id)->get()->count();
                    $allScheduleDays = $allEvent['schedule_days'];
                    $email_per_day = $allEvent['emails_count'];

                    $total_hours = abs( $allEvent['schedule_hour_from'] - $allEvent['schedule_hour_to'] );
                    $mins = 60;
                    $allScheduleDays = $allEvent['schedule_days'];
                    $total_mins = $total_hours*$mins;
                    $array = array();
                    mt_srand(10);
                    while(sizeof($array) < $email_per_day){
                      $number = mt_rand(0,$total_mins);
                      if(!array_key_exists($number,$array)){
                        $array[$number] = $number;
                      }
                    }
                    foreach ($array as $value) {
                        $time_diff = intdiv($value, 60).'.'. ($value % 60);
                        $spreadTime = $allEvent['schedule_hour_from']+$time_diff;
                        $finalMins = str_replace('.', ':', $spreadTime);
                        if($schedule_event == 'daily'){
                            foreach ($allScheduleDays as $allScheduleDay) {
                                switch ($allScheduleDay) {
                                    case '1':
                                        $schedule->command('event:create',[$id])
                                                    ->weeklyOn($allScheduleDay,$finalMins)
                                                    ->timezone($allEvent['timezone']);
                                    break;

                                    case '2':
                                        $schedule->command('event:create',[$id])
                                                    ->weeklyOn($allScheduleDay,$finalMins)
                                                    ->timezone($allEvent['timezone']);
                                    break;

                                    case '3':
                                        $schedule->command('event:create',[$id])
                                                    ->weeklyOn($allScheduleDay,$finalMins)
                                                    ->timezone($allEvent['timezone']);
                                    break;

                                    case '4':
                                        $schedule->command('event:create',[$id])
                                                    ->weeklyOn($allScheduleDay,$finalMins)
                                                    ->timezone($allEvent['timezone']);
                                    break;

                                    case '5':
                                        $schedule->command('event:create',[$id])
                                                    ->weeklyOn($allScheduleDay,$finalMins)
                                                    ->timezone($allEvent['timezone']);
                                    break;

                                    case '6':
                                        $schedule->command('event:create',[$id])
                                                ->weeklyOn($allScheduleDay,$finalMins)
                                                ->timezone($allEvent['timezone']);
                                    break;

                                    case '7':
                                        $schedule->command('event:create',[$id])
                                                    ->weeklyOn($allScheduleDay,$finalMins)
                                                    ->timezone($allEvent['timezone']);
                                    break;
                                }
                            }
                        }else if($schedule_event == 'weekly'){
                            $time_diff = intdiv($value, 60).'.'. ($value % 60);
                            $spreadTime = $allEvent['schedule_hour_from']+$time_diff;
                            $finalMins = str_replace('.', ':', $spreadTime);
                            foreach ($array as $value) {
                                $time_diff = intdiv($value, 60).':'. ($value % 60);
                                $schedule->command('event:create',[$id])
                                        ->weeklyOn($weekDay,$finalMins)
                                        ->timezone($allEvent['timezone']);
                            }
                        }else{
                            $time_diff = intdiv($value, 60).'.'. ($value % 60);
                            $spreadTime = $allEvent['schedule_hour_from']+$time_diff;
                            $finalMins = str_replace('.', ':', $spreadTime);
                            foreach ($array as $value) {
                                $time_diff = intdiv($value, 60).':'. ($value % 60);
                                $schedule->command('event:create',[$id])
                                        ->monthlyOn($monthDay,$finalMins)
                                        ->timezone($allEvent['timezone']);
                            }
                        }
                    } 
                }
            }
        }
        
        // Event Cron End

        // Remove Invalid and Valid Logs start 
        $cron_time  = WebhookCron::first();
  
        if($cron_time->status == 'yes'){
          if($cron_time->cron_time == 24){
           $schedule->command('logs_delete:cron')
                     ->cron('* 12 * * */1');
           }
           if($cron_time->cron_time == 48){
           $schedule->command('logs_delete:cron')
                     ->cron('* 12 * * */2');
           }
           if($cron_time->cron_time == 72){
           $schedule->command('logs_delete:cron')
                     ->cron('* 12 * * */3');
           }
        }
        // Remove Invalid and Valid Logs end

        // Mautic and webhook cron issue start 
        $allRules = Rule::get();
        foreach ($allRules as $allRule) {
            if($allRule['status'] == 'running'){
              if($allRule['schedule_time']=='random'){
                $id = $allRule['id'];
                $total_hours = 24;
                $mins = 60;
                $total_mins = $total_hours*$mins;
                $email_per_day = $allRule['emails_count'];
                $allScheduleDays = $allRule['schedule_days'];
                $array = array();
                mt_srand($id);
                while(sizeof($array)<$email_per_day){
                  $number = mt_rand(0,$total_mins);
                  if(!array_key_exists($number,$array)){
                    $array[$number] = $number;
                  }
                }
                
                foreach ($array as $value) {
                  $time_diff = intdiv($value, 60).':'. ($value % 60);
                  foreach ($allScheduleDays as $allScheduleDay) {
                    $allScheduleDay = (int)$allScheduleDay; 
                    switch ($allScheduleDay) {
                            case '1':
                                $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$time_diff)
                                      ->timezone($allRule['timezone']);
                            break;

                            case '2':
                                $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$time_diff)
                                      ->timezone($allRule['timezone']);
                            break;

                            case '3':
                                $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$time_diff)
                                      ->timezone($allRule['timezone']);
                            break;

                            case '4':
                                $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$time_diff)
                                      ->timezone($allRule['timezone']);
                            break;

                            case '5':
                                $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$time_diff)
                                      ->timezone($allRule['timezone']);
                            break;

                            case '6':
                                $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$time_diff)
                                      ->timezone($allRule['timezone']);
                            break;

                            case '7':
                                $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$time_diff)
                                      ->timezone($allRule['timezone']);
                            break;
                     }
                  }
                }
              }else{
                $id = $allRule['id'];
                $email_per_day = $allRule['emails_count'];
                $total_hours = abs( $allRule['schedule_hour_from'] - $allRule['schedule_hour_to'] );
                $mins = 60;
                $allScheduleDays = $allRule['schedule_days'];
                $total_mins = $total_hours*$mins;
                $array = array();
                mt_srand(10);
                while(sizeof($array)<$email_per_day){
                  $number = mt_rand(0,$total_mins);
                  if(!array_key_exists($number,$array)){
                     $array[$number] = $number;
                  }
                }
                foreach ($array as $value) {
                  $time_diff = intdiv($value, 60).'.'. ($value % 60);
                  $spreadTime = $allRule['schedule_hour_from']+$time_diff;
                  $finalMins = str_replace('.', ':', $spreadTime);
                  foreach ($allScheduleDays as $allScheduleDay) {
                     switch ($allScheduleDay) {
                          case '1':
                              $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$finalMins)
                                      ->timezone($allRule['timezone']);
                          break;

                          case '2':
                             $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$finalMins)
                                      ->timezone($allRule['timezone']);
                          break;

                          case '3':
                             $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$finalMins)
                                      ->timezone($allRule['timezone']);
                          break;

                          case '4':
                             $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$finalMins)
                                      ->timezone($allRule['timezone']);
                          break;

                          case '5':
                             $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$finalMins)
                                      ->timezone($allRule['timezone']);
                          break;

                          case '6':
                             $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$finalMins)
                                      ->timezone($allRule['timezone']);
                          break;

                          case '7':
                             $schedule->command('mauticemail:cron',[$id])
                                      ->weeklyOn($allScheduleDay,$finalMins)
                                      ->timezone($allRule['timezone']);
                          break;
                     }
                  }
                }
              }
            }
        }
        // Mautic and webhook cron issue end 
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
