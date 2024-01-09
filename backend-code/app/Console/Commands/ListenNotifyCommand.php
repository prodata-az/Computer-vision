<?php

namespace App\Console\Commands;

use App\Http\Controllers\Log\GetNewLogController;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PDO;

class ListenNotifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'listen:postgres';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Listen to PostgreSQL notifications';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $controller = new GetNewLogController();

        $dbconn = DB::connection()->getPdo();

        $dbconn->exec("LISTEN new_log");

        while (true) {
            $result = $dbconn->pgsqlGetNotify(PDO::FETCH_ASSOC);
            if (!$result) {
                sleep(1);
                continue;
            }

            $id = json_decode($result['payload']);
            $controller->__invoke((int) $id);
        }
    }
}
