<?php

namespace App\Console\Commands;

use App\Concerns\EstablishesConnections;
use App\Models\Rule;
use Illuminate\Console\Command;
use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;

class ExportEmailsCommand extends Command
{
    use EstablishesConnections;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export emails';

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
        $rules = Rule::query()
            ->when('status', Rule::STATUS_RUNNING)
            ->get();



//        // ApiAuth->newAuth() will accept an array of Auth settings
//        $settings = [
//            'userName'   => 'admin',             // Create a new user
//            'password'   => 'g2fRMCZP',             // Make it a secure password
//        ];
//
//        // Initiate the auth object specifying to use BasicAuth
//        $initAuth = new ApiAuth();
//        $auth = $initAuth->newAuth($settings, 'BasicAuth');
//
//        $apiUrl = 'https://chassetonboss.com';
//
//        $api = new MauticApi();
//
//
////        $stagesApi = $api->newApi('stages', $auth, $apiUrl);
//
////        $response = $stagesApi->
//
//        $contactApi = $api->newApi('contacts', $auth, $apiUrl);
//
//        $response = $contactApi->createBatch([
//            [
//                'email' => 'deathburger777@gmail.com',
//                'stage' => 1
//            ],
//            [
//                'email' => 'deathburger777@yandex.ru',
//                'stage' => 1
//            ],
//        ]);
////
//        dd($response);
    }
}
