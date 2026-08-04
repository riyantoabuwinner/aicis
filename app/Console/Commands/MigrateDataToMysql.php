<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MigrateDataToMysql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-data-to-mysql';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate data from sqlite to mysql';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting data migration from SQLite to MySQL...');

        // Fix the sqlite database path since DB_DATABASE is set to 'aicis'
        config(['database.connections.sqlite.database' => database_path('database.sqlite')]);

        // First, run artisan migrate on mysql to ensure schemas match
        $this->info('Running migrations on MySQL...');
        $this->call('migrate', ['--database' => 'mysql', '--force' => true]);

        // Disable foreign key checks for mysql to allow inserting arbitrary IDs
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');

        try {
            $sqliteTables = DB::connection('sqlite')->select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

            foreach ($sqliteTables as $tableInfo) {
                $table = $tableInfo->name;
                
                // Skip migrations table as it is already handled by artisan migrate
                if ($table === 'migrations') {
                    continue;
                }

                $this->info("Migrating table: $table");

                // Clear existing table data in mysql
                DB::connection('mysql')->table($table)->truncate();

                // Fetch all data from sqlite
                $data = DB::connection('sqlite')->table($table)->get()->map(function($item) {
                    return (array) $item;
                })->toArray();

                if (count($data) > 0) {
                    $chunks = array_chunk($data, 500);
                    $progress = $this->output->createProgressBar(count($chunks));
                    $progress->start();
                    
                    foreach ($chunks as $chunk) {
                        DB::connection('mysql')->table($table)->insert($chunk);
                        $progress->advance();
                    }
                    $progress->finish();
                    $this->newLine();
                } else {
                    $this->line(" No data found.");
                }
            }
            $this->info('Data migration completed successfully!');
        } catch (\Exception $e) {
            $this->error('Error migrating data: ' . $e->getMessage());
        }

        // Re-enable foreign key checks
        DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
