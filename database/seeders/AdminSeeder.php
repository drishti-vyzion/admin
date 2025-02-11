<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'role' => 'admin',
            'name'=> 'admin user',
            'email'=>'admin@yopmail.com',
             'password'=>'admin123'

        ]);

    }
}
