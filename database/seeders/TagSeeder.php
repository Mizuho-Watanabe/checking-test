<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TagSeeder extends Seeder
{
    //固定値を5件投入
        public function run ()
        {
            DB::table('tags')->insert([
                ['name' => '質問'],
                ['name' => 'ご要望'],
                ['name' => '不具合報告'],
                ['name' => 'ご意見'],
                ['name' => 'その他'],
            ]);
        }
}

