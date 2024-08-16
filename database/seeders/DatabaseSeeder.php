<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Aung Kyaw Myint',
            'email' => 'aungkyawmyint@inyalakehotel.com',
            'password' => Hash::make('R0binhood'),
            'department_id' => '1',
            'role_id' => '1',

        ]);
        DB::table('departments')->insert([
            'name' => 'IT Dept',
        ]);
        DB::table('departments')->insert([
            'name' => 'Engineering',
        ]);
        DB::table('departments')->insert([
            'name' => 'Finance',
        ]);
        DB::table('departments')->insert([
            'name' => 'Front Office',
        ]);
        DB::table('departments')->insert([
            'name' => 'Housekeeping',
        ]);
        DB::table('departments')->insert([
            'name' => 'Food Service',
        ]);
        DB::table('departments')->insert([
            'name' => 'Food Production',
        ]);
        DB::table('departments')->insert([
            'name' => 'Marketing',
        ]);
        DB::table('departments')->insert([
            'name' => 'Sales',
        ]);
        DB::table('departments')->insert([
            'name' => 'Catering',
        ]);
        DB::table('departments')->insert([
            'name' => 'Executive',
        ]);
        DB::table('departments')->insert([
            'name' => 'HR Dept',
        ]);
        DB::table('departments')->insert([
            'name' => 'Security',
        ]);
        DB::table('departments')->insert([
            'name' => 'CountryClub',
        ]);
    }
}
