<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\SubLocation;
use App\Models\Internship;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InternshipsController extends Controller
{

    /**
     * Retrieve all internships.
     *
     * @return Internship[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
    {
        return Internship::all();
    }

    /**
     * Retrieve all internships of a student.
     *
     * @param int $studentId
     *
     * @return Internship[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedToStudent(int $studentId) : Collection
    {
        $internships = Student::findOrFail($studentId)->internships;

        if (count($internships) === 0) {
            throw new NotFoundHttpException();
        }

        return $internships;
    }

    /**
     * Retrieve a internship.
     *
     * @param int $id
     *
     * @return Internship
     */
    public function show(int $id) : Internship
    {
        return Internship::findOrFail($id);
    }
    
    /**
     * Create a student's internship.
     *
     * @param Request $request
     * @param int $studentId
     *
     * @return Internship
     */
    public function createRelatedToStudent(Request $request, int $studentId) : Internship
    {
        $student = Student::findOrFail($studentId);
        
        $input = $request->request->all();
        unset($input['location']);
        if (isset($input['sub_location']) === true) {
            unset($input['sub_location']);
        }

        $internship = new Internship($input);
        
        $location = Location::where('name', $request->request->get('location'))->first();
        $internship->location()->associate($location);
        
        if ($request->request->has('sub_location') === true) {
            $subLocation = SubLocation::where('name', $request->request->get('sub_location'))->first();
            $internship->subLocation()->associate($subLocation);
        }

        $student->internships()->save($internship);

        return $internship;
    }
    
    /**
     * Modify a internship.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Internship
     */
    public function update(Request $request, int $id) : Internship
    {
        $internship = Internship::findOrFail($id);
        
        $input = $request->request->all();
        unset($input['location']);
        if (isset($input['sub_location']) === true) {
            unset($input['sub_location']);
        }
        
        $internship->fill($input);
        
        $location = Location::where('name', $request->request->get('location'))->first();
        $internship->location()->associate($location);
        
        if ($request->request->has('sub_location') === true) {
            $subLocation = SubLocation::where('name', $request->request->get('sub_location'))->first();
            $internship->subLocation()->associate($subLocation);
        } else {
            $internship->subLocation()->dissociate();
        }
        
        $internship->save();
        
        return $internship;
    }

    /**
     * Delete a internship.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $annotation = Internship::findOrFail($id);
        $annotation->delete();
    }

}
