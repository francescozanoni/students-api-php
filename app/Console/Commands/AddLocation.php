<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class AddLocation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'location:add {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a location to be used as internship attribute';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // @todo add input validation

        $name = $this->argument('name');

        DB::insert('insert into locations (name, created_at) values (?, ?)', [$name, date('Y-m-d H:i:s')]);

        $this->info('Location successfully added');

    }

}
