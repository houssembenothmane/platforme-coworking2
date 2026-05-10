<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Client::create([
            'nom'      => 'Admin',
            'email'    => 'admin@coworking.com',
            'password' => 'admin123',
            'role'     => 'admin',
        ]);
    }
}