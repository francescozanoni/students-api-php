<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class ListSubLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sublocation:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List available sub-locations';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $headers = ['id', 'name', 'created_at', 'updated_at', 'deleted_at'];

        $records = json_decode(json_encode(DB::select('select * from sub_locations')), true);

        $this->table($headers, $records);

    }

}
