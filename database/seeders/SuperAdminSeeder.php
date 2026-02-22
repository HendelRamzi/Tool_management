<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;


// Create the super admin user
class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $data = [
            'name' => "admin", 
            "email" => "admin@admin.com",
            'password' => Hash::make('admin'), 
       ]; 

       $super_admin = User::create($data);


        //Make the user as super_admin role
        $super_admin->assignRole(UserRole::super_admin);
    }
}
