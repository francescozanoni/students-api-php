<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\SeminarAttendance;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SeminarAttendancesController extends Controller
{

    /**
     * Retrieve all seminar attendances.
     *
     * @return SeminarAttendance[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
    {
        return SeminarAttendance::all();
    }

    /**
     * Retrieve all seminar attendances of a student.
     *
     * @param int $studentId
     *
     * @return SeminarAttendance[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedToStudent(int $studentId) : Collection
    {
        $seminarAttendances = Student::findOrFail($studentId)->annotations;

        if (count($seminarAttendances) === 0) {
            throw new NotFoundHttpException();
        }

        return $seminarAttendances;
    }

    /**
     * Retrieve a seminar attendance.
     *
     * @param int $id
     *
     * @return SeminarAttendance
     */
    public function show(int $id) : SeminarAttendance
    {
        return SeminarAttendance::findOrFail($id);
    }

    /**
     * Create a student's seminar attendance.
     *
     * @param Request $request
     * @param int $studentId
     *
     * @return SeminarAttendance
     */
    public function createRelatedToStudent(Request $request, int $studentId) : SeminarAttendance
    {
        $student = Student::findOrFail($studentId);

        $seminarAttendance = new SeminarAttendance($request->request->all());

        $student->seminarAttendances()->save($seminarAttendance);

        return $seminarAttendance;
    }

    /**
     * Modify a seminar attendance.
     *
     * @param Request $request
     * @param int $id
     *
     * @return SeminarAttendance
     */
    public function update(Request $request, int $id) : SeminarAttendance
    {
        $seminarAttendance = SeminarAttendance::findOrFail($id);
        $seminarAttendance->fill($request->request->all());
        $seminarAttendance->save();
        return $seminarAttendance;
    }

    /**
     * Delete a seminar attendance.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $seminarAttendance = SeminarAttendance::findOrFail($id);
        $seminarAttendance->delete();
    }

}
