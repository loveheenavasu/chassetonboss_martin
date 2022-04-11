<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Dmitry',
            'email' => 'deathburger777@gmail.com'
        ]);

        // \App\Models\User::factory(10)->create();
    }
}
