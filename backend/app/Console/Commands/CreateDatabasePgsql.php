<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CreateDatabasePgsql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:create-database-pgsql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create database if not exists. Only pgsql.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $defaultConnection = config('database.default');
        $connection = config("database.connections.{$defaultConnection}");

        if (($connection['driver'] ?? null) !== 'pgsql') {
            $this->error('Database driver must be pgsql');
            return self::FAILURE;
        }

        $databaseName = $connection['database'] ?? null;

        if (!$databaseName) {
            $this->error('Database name cannot be empty');
            return self::FAILURE;
        }

        Config::set("database.connections.{$defaultConnection}.database", 'postgres');

        DB::purge($defaultConnection);
        $pdo = DB::connection($defaultConnection)->getPdo();

        $stmt = $pdo->prepare('SELECT 1 FROM pg_database WHERE datname = :database');
        $stmt->execute(['database' => $databaseName]);

        $exists = $stmt->fetchColumn();

        if ($exists) {
            $this->info('Database already exists!');
            return self::SUCCESS;
        }

        $safeDatabaseName = str_replace('"', '""', $databaseName);

        DB::connection($defaultConnection)
            ->statement(sprintf('CREATE DATABASE "%s"', $safeDatabaseName));

        $this->info('Database has been created!');

        Config::set("database.connections.{$defaultConnection}.database", $databaseName);
        DB::purge($defaultConnection);

        return self::SUCCESS;
    }
}
