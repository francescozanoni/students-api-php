<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\OshCourseAttendance;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OshCourseAttendancesController extends Controller
{

    /**
     * Retrieve all Occupational Safety and Health course attendances.
     *
     * @return OshCourseAttendance[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
    {
        $oshCourseAttendances = OshCourseAttendance::all();

        if ($oshCourseAttendances->isEmpty() === true) {
            throw new NotFoundHttpException();
        }

        return $oshCourseAttendances;
    }

    /**
     * Retrieve all Occupational Safety and Health course attendances of a student.
     *
     * @param int $studentId
     *
     * @return OshCourseAttendance[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedToStudent(int $studentId) : Collection
    {
        $oshCourseAttendances = Student::findOrFail($studentId)->oshCourseAttendances;

        if (count($oshCourseAttendances) === 0) {
            throw new NotFoundHttpException();
        }

        return $oshCourseAttendances;
    }

    /**
     * Retrieve an Occupational Safety and Health course attendance.
     *
     * @param int $id
     *
     * @return OshCourseAttendance
     */
    public function show(int $id) : OshCourseAttendance
    {
        return OshCourseAttendance::findOrFail($id);
    }

    /**
     * Create a student's Occupational Safety and Health course attendance.
     *
     * @param Request $request
     * @param int $studentId
     *
     * @return OshCourseAttendance
     */
    public function createRelatedToStudent(Request $request, int $studentId) : OshCourseAttendance
    {
        $student = Student::findOrFail($studentId);

        $oshCourseAttendance = new OshCourseAttendance($request->request->all());

        $student->oshCourseAttendances()->save($oshCourseAttendance);

        return $oshCourseAttendance;
    }

    /**
     * Modify an Occupational Safety and Health course attendance.
     *
     * @param Request $request
     * @param int $id
     *
     * @return OshCourseAttendance
     */
    public function update(Request $request, int $id) : OshCourseAttendance
    {
        $oshCourseAttendance = OshCourseAttendance::findOrFail($id);
        $oshCourseAttendance->fill($request->request->all());
        $oshCourseAttendance->save();
        return $oshCourseAttendance;
    }

    /**
     * Delete an Occupational Safety and Health course attendance.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $oshCourseAttendance = OshCourseAttendance::findOrFail($id);
        $oshCourseAttendance->delete();
    }

}
