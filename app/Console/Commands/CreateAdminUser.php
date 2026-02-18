<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin';
    protected $description = 'Create a new admin user';

    public function handle()
    {
        $name = $this->ask('Admin name');
        $email = $this->ask('Admin email');
        $password = $this->secret('Admin password');
        
        User::create([
            'name' => $name,
            'email' => $email,
            'password' => bcrypt($password),
            'role' => 'admin',
        ]);
        
        $this->info('Admin user created successfully!');
    }
}
