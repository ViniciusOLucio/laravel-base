<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $user = User::factory()->create([
            'name' => 'Usuario',
            'email' => 'test@example.com',
           'password' => 'test@example.com',

        ]);
       $user->assignRole('user');

        $lawyer = User::factory()->create([
            'name' => 'Advogado',
            'email' => 'advogado@example.com',
            'password' => 'advogado@example.com',

        ]);
        $lawyer->assignRole('lawyer');



        $admin = User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@example.com',
            'password' => 'admin@example.com',

        ]);
        $admin->assignRole('admin');


        $super_admin = User::factory()->create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@example.com',
            'password' => 'superadmin@example.com',

        ]);
        $super_admin->assignRole('super_admin');
    }

}
