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

    /**
     * Retrieve a stage.
     *
     * @param int $id
     *
     * @return Stage
     */
    public function show(int $id) : Stage
    {
        return Stage::findOrFail($id);
    }

    /**
     * Delete a stage.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $annotation = Stage::findOrFail($id);
        $annotation->delete();
    }

}
