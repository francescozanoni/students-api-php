<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\SubLocation;
use App\Models\Stage;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
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
     * Create a student's stage.
     *
     * @param Request $request
     * @param int $studentId
     *
     * @return Stage
     */
    public function createRelatedToStudent(Request $request, int $studentId) : Stage
    {
        $student = Student::findOrFail($studentId);
        
        $input = $request->request->all();
        unset($input['location']);
        if (isset($input['sub_location']) === true) {
            unset($input['sub_location']);
        }

        $stage = new Stage($input);
        
        $location = Location::where('name', $request->request->get('location'))->first();
        $stage->location()->associate($location);
        
        if ($request->request->has('sub_location') === true) {
            $subLocation = SubLocation::where('name', $request->request->get('sub_location'))->first();
            $stage->subLocation()->associate($subLocation);
        }

        $student->stages()->save($stage);

        return $stage;
    }
    
    /**
     * Modify a stage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Stage
     */
    public function update(Request $request, int $id) : Stage
    {
        $stage = Stage::findOrFail($id);
        
        $input = $request->request->all();
        unset($input['location']);
        if (isset($input['sub_location']) === true) {
            unset($input['sub_location']);
        }
        
        $stage->fill($input);
        
        $location = Location::where('name', $request->request->get('location'))->first();
        $stage->location()->associate($location);
        
        if ($request->request->has('sub_location') === true) {
            $subLocation = SubLocation::where('name', $request->request->get('sub_location'))->first();
            $stage->subLocation()->associate($subLocation);
        } else {
            $stage->subLocation()->dissociate();
        }
        
        $stage->save();
        
        return $stage;
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
