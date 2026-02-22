<?php

namespace Database\Seeders;

use App\Models\Tool;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Password;


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
            'password' => Password::hash('admin'), 
       ]; 

       $super_admin = User::create($data);


        //Make the user as super_admin role
        Artisan::call('shield:super-admin --user='. $super_admin->id);
    }
}
