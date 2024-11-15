<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'user']);
        $user = User::create([
            'name' => 'alaa',
            'email' => 'alaa@gmail.com',
            'password' => '12345678'
        ]);
        $user->assignRole('admin');
    }
}
