<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller
{

    /**
     * Retrieve all students.
     *
     * @return Student[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Student::all();
    }

    /**
     * Create a student.
     *
     * @param Request $request
     *
     * @return Student
     */
    public function store(Request $request)
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
    public function show(int $id)
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
    public function update(Request $request, int $id)
    {
        $student = Student::findOrFail($id);
        $student->fill($request->request->all());
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
