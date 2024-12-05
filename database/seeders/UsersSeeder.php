<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        
        \App\Models\User::create([
            'id' => '1',
            'name' => 'Master Adm',
            'email' => 'a@b.c',
            'locked' => false,
            'password' => '$2y$10$SVTK3/gdSA4fNNEmTHhDxe8zcOoZwuCkY0e9qgUvPR4NW8B.su9EG' // password 12345678
        ]);
        
        \App\Models\User::create([
            'id' => '2',
            'name' => 'Other Adm',
            'email' => 'b@c.d',
            'locked' => true,
            'password' => '$2y$10$SVTK3/gdSA4fNNEmTHhDxe8zcOoZwuCkY0e9qgUvPR4NW8B.su9EG' // password 12345678
        ]);
        
    }
}
