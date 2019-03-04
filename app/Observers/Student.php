<?php
declare(strict_types = 1);

namespace App\Observers;

use App\Models\Student as StudentModel;

class Student
{

    /**
     * Handle the StudentModel "deleted" event.
     *
     * @param  StudentModel $student
     */
    public function deleted(StudentModel $student)
    {
        $student->annotations()->delete();
        $student->stages()->delete();
        $student->educationalActivityAttendances()->delete();
    }

}