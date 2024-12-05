<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(UsersProdSeeder::class);
        $this->call(BoardsProdSeeder::class);
        $this->call(ConfiguracaosProdSeeder::class);
        $this->call(RegrasProdSeeder::class);
        $this->call(AdminRightProdSeeder::class);
    }
}
