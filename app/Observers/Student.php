<?php
declare(strict_types = 1);

namespace App\Observers;

use App\Models\Student as StudentModel;

class Student
{

    /**
     * Handle the StudentModel "deleting" event.
     *
     * @param StudentModel $student
     */
    public function deleting(StudentModel $student)
    {
        // "deleting" event does not work on cascade.
        foreach ($student->internships as $internship) {
            $internship->evaluation()->delete();
            $internship->interruptionReport()->delete();
        }
        
        $student->annotations()->delete();
        $student->internships()->delete();
        $student->educationalActivityAttendances()->delete();
        $student->eligibilities()->delete();
        $student->oshCourseAttendances()->delete();
    }

}