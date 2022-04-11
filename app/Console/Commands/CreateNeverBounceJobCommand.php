<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateNeverBounceJobCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'neverbounce:create-job';

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
        // Build json
//        $json = [
//            [
//                'id' => '12345',
//                'email' => 'support@neverbounce.com',
//                'name' => 'Fred McValid',
//            ],
//            [
//                'id' => '12346',
//                'email' => 'invalid@neverbounce.com',
//                'name' => 'Bob McInvalid',
//            ],
//        ];
//
//
//        // Get status from specific job
//        $job = \NeverBounce\Jobs::create(
//            $json,
//            \NeverBounce\Jobs::SUPPLIED_INPUT,
//            'Created from Array.csv',
//            false,
//            true,
//            true
//        );
//
//        var_dump($job);

        $status = \NeverBounce\Jobs::results(10123768);

        dd($status);
    }
}
