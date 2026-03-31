<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BackupData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:data {--type=all : Type of backup (database, files, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create backups of database and/or files';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $timestamp = now()->format('Y-m-d_H-i-s');

        // Create backup directory if it doesn't exist
        $backupDir = storage_path('backups');
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        $this->info("Starting backup process...");

        $backups = [];

        // Database backup
        if ($type === 'all' || $type === 'database') {
            $dbBackup = $this->backupDatabase($timestamp);
            if ($dbBackup) {
                $backups[] = $dbBackup;
                $this->info("✓ Database backup created: {$dbBackup}");
            }
        }

        // Files backup
        if ($type === 'all' || $type === 'files') {
            $filesBackup = $this->backupFiles($timestamp);
            if ($filesBackup) {
                $backups[] = $filesBackup;
                $this->info("✓ Files backup created: {$filesBackup}");
            }
        }

        if (empty($backups)) {
            $this->error('No backups were created.');
            return 1;
        }

        // Log successful backup
        Log::info('Automated backup completed', [
            'timestamp' => $timestamp,
            'type' => $type,
            'backups' => $backups
        ]);

        $this->info('Backup process completed successfully!');
        $this->line('Backup files:');
        foreach ($backups as $backup) {
            $this->line("  - {$backup}");
        }

        return 0;
    }

    /**
     * Create database backup
     */
    private function backupDatabase(string $timestamp): ?string
    {
        try {
            $dbPath = database_path('database.sqlite');
            $backupPath = storage_path("backups/database_{$timestamp}.sql");

            if (!file_exists($dbPath)) {
                $this->error('Database file not found');
                return null;
            }

            // Use sqlite3 command to dump database
            $command = "sqlite3 \"{$dbPath}\" .dump > \"{$backupPath}\" 2>&1";
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $this->error('Database backup failed: ' . implode("\n", $output));
                return null;
            }

            // Verify backup file was created and has content
            if (!file_exists($backupPath) || filesize($backupPath) === 0) {
                $this->error('Database backup file was not created or is empty');
                return null;
            }

            return "database_{$timestamp}.sql";

        } catch (\Exception $e) {
            $this->error('Database backup error: ' . $e->getMessage());
            Log::error('Database backup failed', ['error' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Create files backup
     */
    private function backupFiles(string $timestamp): ?string
    {
        try {
            $backupPath = storage_path("backups/files_{$timestamp}.tar.gz");

            // Files and directories to backup
            $pathsToBackup = [
                public_path('storage'),
                storage_path('app/public'),
                storage_path('gdpr-exports'),
            ];

            // Filter out non-existent paths
            $existingPaths = array_filter($pathsToBackup, function ($path) {
                return file_exists($path);
            });

            if (empty($existingPaths)) {
                $this->warn('No files directories found to backup');
                return null;
            }

            // Create tar.gz archive
            $pathsString = '"' . implode('" "', $existingPaths) . '"';
            $command = "tar -czf \"{$backupPath}\" {$pathsString} 2>/dev/null";
            exec($command, $output, $returnCode);

            if ($returnCode !== 0) {
                $this->error('Files backup failed: ' . implode("\n", $output));
                return null;
            }

            // Verify backup file was created
            if (!file_exists($backupPath)) {
                $this->error('Files backup archive was not created');
                return null;
            }

            return "files_{$timestamp}.tar.gz";

        } catch (\Exception $e) {
            $this->error('Files backup error: ' . $e->getMessage());
            Log::error('Files backup failed', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
