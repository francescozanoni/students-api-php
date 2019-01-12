<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller
{

    /**
     * Retrieve all students.
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
     * @return Student[]|\Illuminate\Database\Eloquent\Collection
     */
    public function store(Request $request)
    {
        return Student::all();
    }

    /**
     * Retrieve a student.
     *
     * @param int $id
     *
     * @return
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
     * @return Student[]|\Illuminate\Database\Eloquent\Collection
     */
    public function update(Request $request, int $id)
    {
        return Student::all();
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
