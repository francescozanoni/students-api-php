<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Stage;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * Retrieve all stages of a student.
     *
     * @param int $studentId
     *
     * @return Stage[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedToStudent(int $studentId) : Collection
    {
        $stages = Student::findOrFail($studentId)->stages;

        if (count($stages) === 0) {
            throw new NotFoundHttpException();
        }

        return $stages;
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
