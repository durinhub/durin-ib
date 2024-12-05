<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AdminRight;
use App\Enums\AdminRightsEnum;

class AdminRightProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // user 1
        AdminRight::create([
            'user_id' => '1',
            'right' => AdminRightsEnum::DoAll,
        ]);
    }
}
