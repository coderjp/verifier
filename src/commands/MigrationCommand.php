<?php namespace Coderjp\Verifier;
/**
 * This file is part of Verifier.
 *
 * @license MIT
 * @package Coderjp\Verifier
 */

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class MigrationCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'verifier:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration for Verifier';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {

        $this->laravel->view->addNamespace('verifier', substr(__DIR__, 0, -8).'views');

        $tables = Config::get('verifier.tables');

        $this->line('');
        $this->info( 'Tables: '. implode(',', $tables) );

        $msg = 'A migration that adds Verifier columns to '. implode(',', $tables) .' will be created in '.
            'database/migrations directory';

        $this->comment($msg);
        $this->line('');

        if ($this->confirm("Proceed with the migration creation? [Yes|no]", "Yes")) {

            $this->line('');
            $this->info("Creating migration...");

            if ($this->createMigration($tables)) {

                $this->info("Migration successfully created!");

            } else {

                $this->error(
                    "Couldn't create migration.\n Check the write permissions".
                    " within the database/migrations directory."
                );

            }

            $this->line('');
        }
    }

    /**
     * Create the migration.
     *
     * @param array $tables
     *
     * @return bool
     */
    protected function createMigration($tables)
    {
        $codeColumn = Config::get('verifier.store_column');
        $flagColumn = Config::get('verifier.flag_column');

        $migrationFile = base_path("/database/migrations")."/".date('Y_m_d_His')."_verifier_add_columns.php";

        $data = compact('codeColumn', 'flagColumn', 'tables');

        $output = $this->laravel->view->make('verifier::generators.migration')->with($data)->render();

        if (!file_exists($migrationFile) && $fs = fopen($migrationFile, 'x')) {

            fwrite($fs, $output);
            fclose($fs);
            return true;

        }

        return false;

    }
}
