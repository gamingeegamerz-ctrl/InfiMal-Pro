<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class UnsuspendUser extends Command
{
    protected $signature = 'infimail:unsuspend-user {user : User ID or email}';
    protected $description = 'Unsuspend an Infimail user after list hygiene review.';

    public function handle(): int
    {
        $identifier = $this->argument('user');
        $user = User::query()
            ->when(is_numeric($identifier), fn ($query) => $query->whereKey((int) $identifier), fn ($query) => $query->where('email', $identifier))
            ->first();

        if (! $user) {
            $this->error('User not found.');
            return self::FAILURE;
        }

        $user->forceFill(['suspended' => false, 'suspension_reason' => null])->save();
        $this->info('User '.$user->email.' has been unsuspended.');

        return self::SUCCESS;
    }
}
