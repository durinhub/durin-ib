<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AdminRight;
use App\Enums\AdminRightsEnum;

class AdminRightSeeder extends Seeder
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

        // user 3
        AdminRight::create([
            'user_id' => '3',
            'right' => AdminRightsEnum::CreateBoards,
        ]);
        AdminRight::create([
            'user_id' => '3',
            'right' => AdminRightsEnum::DeleteBoards,
        ]);
        AdminRight::create([
            'user_id' => '3',
            'right' => AdminRightsEnum::LimpaCache,
        ]);
        AdminRight::create([
            'user_id' => '3',
            'right' => AdminRightsEnum::BlockNewPosts,
        ]);
        AdminRight::create([
            'user_id' => '3',
            'right' => AdminRightsEnum::BypassAdmCookie,
        ]);
        AdminRight::create([
            'user_id' => '3',
            'right' => AdminRightsEnum::NoticiasCrud,
        ]);
    }
}
