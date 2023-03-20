<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

use Hash;

class CreateAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name'       => 'Admin',
            'login_type' => 'Admin',
            'type' => 'Admin',
            'email'      => 'admin@gmail.com',
            'mobile'     => 111,            
            'password'   => Hash::make('Admin@123'),
        ]);
       
    }
}
