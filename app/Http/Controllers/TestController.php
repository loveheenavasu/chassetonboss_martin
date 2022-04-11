<?php

namespace App\Http\Controllers;

use Faker\Generator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use League\Csv\Writer;
use Mautic\Auth\ApiAuth;
use Mautic\MauticApi;

class TestController extends Controller
{
    public function generateEmails(Generator $faker)
    {
        $records = Collection::times(1000)->map(function ($index) use ($faker) {
            return [
                $faker->safeEmail,
                $faker->firstName,
                $faker->phoneNumber,
            ];
        })->prepend([
            'Email',
            'Name',
            'Phone'
        ])->toArray();

        $writer = Writer::createFromString();
        $writer->insertAll($records);

        return response($writer->getContent(), 200, [
            'Content-type' => 'text/plain',
            'Content-Disposition' => sprintf('attachment; filename="%s"', 'test.csv'),
            'Content-Length' => strlen($writer->getContent())
        ]);
    }

    public function mautic()
    {
        // ApiAuth->newAuth() will accept an array of Auth settings
        $settings = [
            'userName'   => 'admin',             // Create a new user
            'password'   => 'g2fRMCZP',             // Make it a secure password
        ];

        // Initiate the auth object specifying to use BasicAuth
        $initAuth = new ApiAuth();
        $auth = $initAuth->newAuth($settings, 'BasicAuth');

        $apiUrl = 'https://chassetonboss.com';

        $api        = new MauticApi();


//        $stagesApi = $api->newApi('stages', $auth, $apiUrl);

//        $response = $stagesApi->

        $contactApi = $api->newApi('contacts', $auth, $apiUrl);

        $response = $contactApi->createBatch([
            [
                'email' => 'deathburger777@gmail.com',
                'stage' => 1
            ],
//            [
//                'email' => 'pew5@yopmail.com',
//                'stage' => 1
//            ],
        ]);
//
        dd($response);

//        $fields = $contactApi->getFieldList();
//
//        var_dump($fields);
//die;
//        dd($response);
//        $fields = $contactApi->getOwners();

//        var_dump($fields);

//        var_dump($contactApi);
        // Nothing else to do ... It's ready to use.
        // Just pass the auth object to the API context you are creating.
    }
}
