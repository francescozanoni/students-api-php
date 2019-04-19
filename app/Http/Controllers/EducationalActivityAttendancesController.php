<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\EducationalActivityAttendance;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EducationalActivityAttendancesController extends Controller
{

    /**
     * Retrieve all educational activity attendances.
     *
     * @return EducationalActivityAttendance[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
    {
        return EducationalActivityAttendance::all();
    }

    /**
     * Retrieve all educational activity attendances of a student.
     *
     * @param int $studentId
     *
     * @return EducationalActivityAttendance[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedToStudent(int $studentId) : Collection
    {
        $educationalActivityAttendances = Student::findOrFail($studentId)->educationalActivityAttendances;

        if (count($educationalActivityAttendances) === 0) {
            throw new NotFoundHttpException();
        }

        return $educationalActivityAttendances;
    }

    /**
     * Retrieve a educational activity attendance.
     *
     * @param int $id
     *
     * @return EducationalActivityAttendance
     */
    public function show(int $id) : EducationalActivityAttendance
    {
        return EducationalActivityAttendance::findOrFail($id);
    }

    /**
     * Create a student's educational activity attendance.
     *
     * @param Request $request
     * @param int $studentId
     *
     * @return EducationalActivityAttendance
     */
    public function createRelatedToStudent(Request $request, int $studentId) : EducationalActivityAttendance
    {
        $student = Student::findOrFail($studentId);

        $educationalActivityAttendance = new EducationalActivityAttendance($request->request->all());

        $student->educationalActivityAttendances()->save($educationalActivityAttendance);

        return $educationalActivityAttendance;
    }

    /**
     * Modify a educational activity attendance.
     *
     * @param Request $request
     * @param int $id
     *
     * @return EducationalActivityAttendance
     */
    public function update(Request $request, int $id) : EducationalActivityAttendance
    {
        $educationalActivityAttendance = EducationalActivityAttendance::findOrFail($id);

        $input = $request->request->all();

        // "end_date" is an optional field: in case it's removed (not available within input),
        // it must be set to null, otherwise the change is ignored by $educationalActivityAttendance->fill($input).
        if (array_key_exists('end_date', $input) === false) {
            $input['end_date'] = null;
        }

        $educationalActivityAttendance->fill($input);

        $educationalActivityAttendance->save();

        return $educationalActivityAttendance;
    }

    /**
     * Delete a educational activity attendance.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $educationalActivityAttendance = EducationalActivityAttendance::findOrFail($id);
        $educationalActivityAttendance->delete();
    }

}
