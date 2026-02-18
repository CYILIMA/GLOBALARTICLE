<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default categories for production
        $this->call([
            CategoryImportSeeder::class,
        ]);
        
        // Note: Create your first admin user manually via:
        // php artisan tinker
        // User::create(['name' => 'Your Name', 'email' => 'your@email.com', 'password' => bcrypt('your-secure-password'), 'role' => 'admin']);
    }
}
