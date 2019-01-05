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
}