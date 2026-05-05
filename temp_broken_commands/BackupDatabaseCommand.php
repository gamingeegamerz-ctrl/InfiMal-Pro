<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Process;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'infimal:backup-db';
    protected $description = 'Create a daily database backup snapshot in local storage';

    public function handle(): int
    {
        $connection = config('database.default');
        $config = (array) config("database.connections.{$connection}", []);

        $backupDir = storage_path('app/backups/database/'.now()->format('Y/m'));
        File::ensureDirectoryExists($backupDir);

        return match ($config['driver'] ?? '') {
            'sqlite' => $this->backupSqlite($config, $backupDir),
            'mysql', 'mariadb' => $this->backupMysql($config, $backupDir),
            'pgsql' => $this->backupPgsql($config, $backupDir),
            default => $this->unsupportedDriver($config['driver'] ?? 'unknown'),
        };
    }

    private function backupSqlite(array $config, string $backupDir): int
    {
        $databasePath = $config['database'] ?? null;
        $driver = (string) config("database.connections.{$connection}.driver");
        $backupDir = storage_path('app/backups/database/'.now()->format('Y/m'));
        File::ensureDirectoryExists($backupDir);

        $filename = 'backup-'.now()->format('Y-m-d_His');

        return match ($driver) {
            'sqlite' => $this->backupSqlite($connection, $backupDir.'/'.$filename.'.sqlite'),
            'mysql' => $this->backupMysql($connection, $backupDir.'/'.$filename.'.sql'),
            'pgsql' => $this->backupPgsql($connection, $backupDir.'/'.$filename.'.sql'),
            default => $this->unsupportedDriver($driver),
        };
    }

    private function backupSqlite(string $connection, string $target): int
    {
        $databasePath = config("database.connections.{$connection}.database");
        if (! $databasePath || ! File::exists($databasePath)) {
            $this->error('SQLite database file not found for backup.');
            return self::FAILURE;
        }

        $backupFile = $backupDir.'/backup-'.now()->format('Y-m-d_His').'.sqlite';
        File::copy($databasePath, $backupFile);
        $this->info('SQLite backup created: '.$backupFile);
        File::copy($databasePath, $target);
        $this->info('SQLite backup created: '.$target);

        return self::SUCCESS;
    }

    private function backupMysql(array $config, string $backupDir): int
    {
        $backupFile = $backupDir.'/backup-'.now()->format('Y-m-d_His').'.sql.gz';
        $command = [
            'sh', '-lc',
            sprintf(
                'mysqldump -h%s -P%s -u%s -p\'%s\' --single-transaction --quick --lock-tables=false %s | gzip > %s',
                escapeshellarg((string) ($config['host'] ?? '127.0.0.1')),
                escapeshellarg((string) ($config['port'] ?? '3306')),
                escapeshellarg((string) ($config['username'] ?? 'root')),
                str_replace("'", "'\\''", (string) ($config['password'] ?? '')),
                escapeshellarg((string) ($config['database'] ?? '')),
                escapeshellarg($backupFile)
            ),
        ];

        return $this->runProcess($command, 'MySQL/MariaDB', $backupFile);
    }

    private function backupPgsql(array $config, string $backupDir): int
    {
        $backupFile = $backupDir.'/backup-'.now()->format('Y-m-d_His').'.sql.gz';
        $command = [
            'sh', '-lc',
            sprintf(
                'PGPASSWORD=%s pg_dump -h %s -p %s -U %s -d %s --no-owner --no-privileges | gzip > %s',
                escapeshellarg((string) ($config['password'] ?? '')),
                escapeshellarg((string) ($config['host'] ?? '127.0.0.1')),
                escapeshellarg((string) ($config['port'] ?? '5432')),
                escapeshellarg((string) ($config['username'] ?? 'postgres')),
                escapeshellarg((string) ($config['database'] ?? '')),
                escapeshellarg($backupFile)
            ),
        ];

        return $this->runProcess($command, 'PostgreSQL', $backupFile);
    }

    private function runProcess(array $command, string $label, string $backupFile): int
    {
        $process = new Process($command);
        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful() || ! File::exists($backupFile)) {
            $this->error($label.' backup failed: '.$process->getErrorOutput());
            return self::FAILURE;
        }

        $this->info($label.' backup created: '.$backupFile);

    private function backupMysql(string $connection, string $target): int
    {
        $host = config("database.connections.{$connection}.host");
        $port = config("database.connections.{$connection}.port", 3306);
        $database = config("database.connections.{$connection}.database");
        $username = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");

        $command = sprintf(
            'mysqldump --host=%s --port=%s --user=%s --password=%s %s > %s',
            escapeshellarg((string) $host),
            escapeshellarg((string) $port),
            escapeshellarg((string) $username),
            escapeshellarg((string) $password),
            escapeshellarg((string) $database),
            escapeshellarg($target)
        );

        $result = Process::run($command);

        if (! $result->successful()) {
            $this->error('MySQL backup failed: '.$result->errorOutput());
            return self::FAILURE;
        }

        $this->info('MySQL backup created: '.$target);
        return self::SUCCESS;
    }

    private function backupPgsql(string $connection, string $target): int
    {
        $host = config("database.connections.{$connection}.host");
        $port = config("database.connections.{$connection}.port", 5432);
        $database = config("database.connections.{$connection}.database");
        $username = config("database.connections.{$connection}.username");
        $password = config("database.connections.{$connection}.password");

        $command = sprintf(
            'PGPASSWORD=%s pg_dump --host=%s --port=%s --username=%s --format=plain --no-owner --no-privileges %s > %s',
            escapeshellarg((string) $password),
            escapeshellarg((string) $host),
            escapeshellarg((string) $port),
            escapeshellarg((string) $username),
            escapeshellarg((string) $database),
            escapeshellarg($target)
        );

        $result = Process::run($command);

        if (! $result->successful()) {
            $this->error('PostgreSQL backup failed: '.$result->errorOutput());
            return self::FAILURE;
        }

        $this->info('PostgreSQL backup created: '.$target);
        return self::SUCCESS;
    }

    private function unsupportedDriver(string $driver): int
    {
        $this->error('Unsupported database driver for backup: '.$driver);

        return self::FAILURE;
    }
        $this->error("Unsupported database driver for backup: {$driver}");
        return self::FAILURE;
    }
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
