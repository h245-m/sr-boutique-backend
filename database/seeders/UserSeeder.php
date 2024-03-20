<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $clientRole = Role::create(['name' => 'client']);

        $admin = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('12345678'),
            'phone' => '0123456789'
        ]);

        $client = User::create([
            'name' => 'Kimo',
            'email' => 'kareemhussen500@gmail.com',
            'password' => bcrypt('12345678'),
            'phone' => '0123456789'
        ]);

        $admin->assignRole($adminRole);
        $client->assignRole($clientRole);


    }
}
