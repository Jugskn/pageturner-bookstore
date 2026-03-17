<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Book;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'name' => 'John Customer',
            'email' => 'customer@test.com',
            'password' => bcrypt('password'),
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        $categories = [
            'Fiction',
            'Science & Technology',
            'History & Politics',
            'Self-Help & Motivation',
            'Children\'s Literature',
        ];

        foreach ($categories as $name) {
            Category::create(['name' => $name]);
        }

        Book::factory(20)->create();
    }
}
