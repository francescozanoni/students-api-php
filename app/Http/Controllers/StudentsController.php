<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Resources\Student as StudentResource;

class StudentsController extends Controller
{

    /**
     * Retrieve all students.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return StudentResource::collection(Student::all());
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
        return Student::create($request->all());
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
        $student->fill($request->all());
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
