<?php

namespace Beike\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
class Sequence extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postgreSQL:sequence';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the last value of the sequence';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $results = DB::select("SELECT sequencename FROM pg_sequences;");
        foreach ($results as $row) {
            $tableName = str_replace('_id_seq', '', $row->sequencename);
            $maxId = $this->getTableMax($tableName);
            if ($maxId) {
                DB::select("SELECT setval('".$row->sequencename."', (SELECT MAX(id) FROM ".$tableName."));");
            }
        }
    }

    private function getTableMax($table)
    {
        $results = DB::selectOne("SELECT MAX(id) as id FROM {$table}");
        return $results->id;
    }
}
