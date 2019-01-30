<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Stage;
use Illuminate\Database\Eloquent\Collection;

class StagesController extends Controller
{

    /**
     * Retrieve all stages.
     *
     * @return Stage[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
    {
        return Stage::all();
    }

}
