<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AdsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Ads::create([
            'resource' => '/res/ad1.png',
            'nome' => 'ad 1',
            'url' => 'https://www.tibia.com',
            'dataexp' => Carbon::now()->addDays(10)
        ]);
    }
}
