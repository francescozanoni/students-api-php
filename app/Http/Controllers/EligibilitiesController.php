<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Eligibility;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EligibilitiesController extends Controller
{

    /**
     * Retrieve all eligibilities.
     *
     * @return Eligibility[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
    {
        $eligibilities = Eligibility::all();

        if ($eligibilities->isEmpty() === true) {
            throw new NotFoundHttpException();
        }

        return $eligibilities;
    }

    /**
     * Retrieve all eligibilities of a student.
     *
     * @param int $studentId
     *
     * @return Eligibility[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedToStudent(int $studentId) : Collection
    {
        $eligibilities = Student::findOrFail($studentId)->eligibilities;

        if (count($eligibilities) === 0) {
            throw new NotFoundHttpException();
        }

        return $eligibilities;
    }

    /**
     * Retrieve an eligibility.
     *
     * @param int $id
     *
     * @return Eligibility
     */
    public function show(int $id) : Eligibility
    {
        return Eligibility::findOrFail($id);
    }

    /**
     * Create a student's eligibility.
     *
     * @param Request $request
     * @param int $studentId
     *
     * @return Eligibility
     */
    public function createRelatedToStudent(Request $request, int $studentId) : Eligibility
    {
        $student = Student::findOrFail($studentId);

        $eligibility = new Eligibility($request->request->all());

        $student->eligibilities()->save($eligibility);

        return $eligibility;
    }

    /**
     * Modify an eligibility.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Eligibility
     */
    public function update(Request $request, int $id) : Eligibility
    {
        $eligibility = Eligibility::findOrFail($id);

        $input = $request->request->all();

        // "notes" is an optional field: in case it's removed (not available within input), it must be set to null,
        // otherwise the change is ignored by $eligibility->fill($input).
        if (array_key_exists('notes', $input) === false) {
            $input['notes'] = null;
        }

        $eligibility->fill($input);

        $eligibility->save();

        return $eligibility;
    }

    /**
     * Delete an eligibility.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $eligibility = Eligibility::findOrFail($id);
        $eligibility->delete();
    }

}
