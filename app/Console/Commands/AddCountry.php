<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;

class AddCountry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'country:add {name} {code}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add a country to be used as student attribute';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // @todo add input validation

        $name = $this->argument('name');
        $code = $this->argument('code');

        DB::insert('insert into countries (name, code, created_at) values (?, ?, ?)', [$name, $code, date('Y-m-d H:i:s')]);

        $this->info('Country successfully added');

    }

}
