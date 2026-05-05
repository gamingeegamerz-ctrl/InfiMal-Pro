<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'create:admin';
    protected $description = 'Create an admin user';

    public function handle()
    {
        $user = User::create([
            'name' => 'Admin',
            'email' => 'kanishghongade@gmail.com',
            'password' => Hash::make('KANashu@257896346123k'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $this->info('Admin user created successfully!');
        $this->info('Email: kanishghongade@gmail.com');
        $this->info('Password: KANashu@257896346123k');
    }
}
