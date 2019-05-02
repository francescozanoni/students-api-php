<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StudentsController extends Controller
{

    /**
     * Retrieve all students.
     *
     * @return Student[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
    {
        $students = Student::all();

        if ($students->isEmpty() === true) {
            throw new NotFoundHttpException();
        }

        return $students;
    }

    /**
     * Create a student.
     *
     * @param Request $request
     *
     * @return Student
     */
    public function store(Request $request) : Student
    {
        return Student::create($request->request->all());
    }

    /**
     * Retrieve a student.
     *
     * @param int $id
     *
     * @return Student
     */
    public function show(int $id) : Student
    {
        return Student::findOrFail($id);
    }

    /**
     * Modify a student.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Student
     */
    public function update(Request $request, int $id) : Student
    {
        $student = Student::findOrFail($id);

        $input = $request->request->all();

        // "phone" is an optional field: in case it's removed (not available within input),
        // it must be set to null, otherwise the change is ignored by $student->fill($input).
        if (array_key_exists('phone', $input) === false) {
            $input['phone'] = null;
        }

        $student->fill($input);

        $student->save();

        return $student;
    }

    /**
     * Delete a student.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
    }

}
