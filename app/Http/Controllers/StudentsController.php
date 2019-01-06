<?php

namespace App\Http\Controllers;

use App\Models\Student;

class StudentsController extends Controller
{
    /**
     * Retrieve all students.
     */
    public function list()
    {
        return Student::all();
    }
    
    /**
     * Retrieve a student.
     */
    public function get(int $id)
    {
        return Student::findOrFail($id);
    }
    
    /**
     * Delete a student.
     */
    public function delete(int $id)
    {
        $student = Student::findOrFail($id);
        $student->delete();
    }
}