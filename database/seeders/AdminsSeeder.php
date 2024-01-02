<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super admin
        Admin::create([
            'name'      => 'Abubaker Elsayed',
            'email'     => 'abubaker@devshop.dev',
            'super'     => true,
            'password'  => Hash::make('superadmin')
        ]);

        // Normal admin
        Admin::create([
            'name'      => 'John Smith',
            'email'     => 'jsmith@devshop.dev',
            'super'     => false,
            'password'  => Hash::make('normaladmin')
        ]);
    }
}
