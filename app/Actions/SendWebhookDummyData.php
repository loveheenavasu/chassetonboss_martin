<?php

namespace App\Actions;

use App\Models\Connection;
use App\Models\EmailInfo;
use Faker\Generator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SendWebhookDummyData
{
    public function send(string $url)
    {
        $faker = app(Generator::class);

        $data = Collection::times(100)->map(function () use ($faker) {
            return [
                'email' => $faker->safeEmail,
                'infos' => [
                    [
                        'type' => 'Phone',
                        'value' => $faker->phoneNumber
                    ],
                    [
                        'type' => 'First Name',
                        'value' => $faker->firstName
                    ]
                ]
            ];
        })->toArray();

        $data = [
            'emails' => [
                'email' => $faker->safeEmail,
                'infos' => collect(EmailInfo::typeOptions())->map(function ($label, $key) use ($faker) {
                    switch ($key) {
                        case 'phone':
                        case 'mobile':
                            $value = $faker->phoneNumber;
                            break;
                        case 'first_name':
                            $value = $faker->firstName;
                            break;
                        case 'last_name':
                            $value = $faker->lastName;
                            break;
                        case 'city':
                            $value = $faker->city;
                            break;
                        default:
                            $value = Str::title($faker->word);
                    }

                    return [
                        'type' => $label,
                        'value' => $value
                    ];
                })->toArray()
            ]
        ];

        $response = Http::post($url, $data);
    }
}
