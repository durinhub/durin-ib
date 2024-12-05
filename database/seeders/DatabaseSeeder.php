<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(BoardsSeeder::class);
        $this->call(ConfiguracaosSeeder::class);
        $this->call(RegrasSeeder::class);
        $this->call(AdminRightSeeder::class);
        $this->call(AdsSeeder::class);
    }
}
