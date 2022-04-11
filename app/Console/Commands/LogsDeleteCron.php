<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EmailLogs;
use App\Models\InvalidEmail;
use App\Models\WebhookCron;
use Illuminate\Support\Facades\File; 
class LogsDeleteCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs_delete:cron';

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
       $validEmails = EmailLogs::all()->delete();
       $InvalidEmails = InvalidEmail::all()->delete();
       if(File::exists(public_path('uploads/errorlogs.txt'))){
           $file =  File::delete(public_path('uploads/errorlogs.txt'));
        }
       return "cron running";
    }
}
