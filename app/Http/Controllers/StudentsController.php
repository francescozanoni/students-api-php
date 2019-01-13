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
     * @return StudentResource[]
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
     * @return StudentResource
     */
    public function store(Request $request)
    {
        $student = Student::create($request->all());
        return new StudentResource($student);
    }

    /**
     * Retrieve a student.
     *
     * @param int $id
     *
     * @return StudentResource
     */
    public function show(int $id)
    {
        return new StudentResource(Student::findOrFail($id));
    }

    /**
     * Modify a student.
     *
     * @param Request $request
     * @param int $id
     *
     * @return StudentResource
     */
    public function update(Request $request, int $id)
    {
        $student = Student::findOrFail($id);
        $student->fill($request->all());
        return new StudentResource($student);
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
