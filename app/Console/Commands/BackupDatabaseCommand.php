<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'infimal:backup-db';
    protected $description = 'Create a daily database backup snapshot in local storage';

    public function handle(): int
    {
        $connection = config('database.default');
        $databasePath = config("database.connections.{$connection}.database");

        if (! $databasePath || ! File::exists($databasePath)) {
            $this->error('Database file not found for backup.');
            return self::FAILURE;
        }

        $backupDir = storage_path('app/backups/database/'.now()->format('Y/m'));
        File::ensureDirectoryExists($backupDir);

        $backupFile = $backupDir.'/backup-'.now()->format('Y-m-d_His').'.sqlite';
        File::copy($databasePath, $backupFile);

        $this->info('Database backup created: '.$backupFile);

        return self::SUCCESS;
    }
}
