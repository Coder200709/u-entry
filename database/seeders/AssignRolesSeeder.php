<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
class AssignRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // First, ensure the 'admin' role exists
        $adminRole = Role::where('name', 'admin')->first();
        if (!$adminRole) {
            // If the role doesn't exist, create it
            $adminRole = Role::create(['name' => 'admin']);
        }
    
        // Find or create the admin user
        $abdulaziz = User::firstOrCreate(
            ['email' => 'abdulazizilla200709@gmail.com'],
            [
                'name' => 'Abdulaziz',  // Provide a name if needed
                'password' => bcrypt('default_password')  // Default password
            ]
        );
    
        // Attach the 'admin' role to Abdulaziz
        $abdulaziz->roles()->sync([$adminRole->id]);
    
        // Create 12 regional accounts
        $regionalUsers = [
            ['email' => 'tashkent@gmail.com', 'name' => 'Tashkent User'],
            ['email' => 'bukhara@gmail.com', 'name' => 'Bukhara User'],
            ['email' => 'samarkand@gmail.com', 'name' => 'Samarkand User'],
            ['email' => 'andijan@gmail.com', 'name' => 'Andijan User'],
            ['email' => 'fergana@gmail.com', 'name' => 'Fergana User'],
            ['email' => 'namangan@gmail.com', 'name' => 'Namangan User'],
            ['email' => 'nukus@gmail.com', 'name' => 'Nukus User'],
            ['email' => 'termez@gmail.com', 'name' => 'Termez User'],
            ['email' => 'urgench@gmail.com', 'name' => 'Urgench User'],
            ['email' => 'karshi@gmail.com', 'name' => 'Karshi User'],
            ['email' => 'navoi@gmail.com', 'name' => 'Navoi User'],
            ['email' => 'kashkadarya@gmail.com', 'name' => 'Kashkadarya User'],
        ];
    
        foreach ($regionalUsers as $userData) {
            // Find or create a user with both email and name, and provide a default password
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => bcrypt('default_password') // Default password
                ]
            );
    
            // Ensure the 'regional' role exists and attach it to each user
            $regionalRole = Role::where('name', 'regional')->first();
            if (!$regionalRole) {
                // Create regional role if it doesn't exist
                $regionalRole = Role::create(['name' => 'regional']);
            }
            
            // Attach the 'regional' role to the user
            $user->roles()->sync([$regionalRole->id]);
        }
    }
    

}
