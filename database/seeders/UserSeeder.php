<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //初期管理者を1件登録
        User::firstOrCreate([ 'email' => 'test@example.com'],
            ['name' => 'Test User',
            'password' => Hash::make('password'),
        ]);
    }
}
