<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Annotation;
use App\Models\Student;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AnnotationsController extends Controller
{

    /**
     * Retrieve all annotations.
     *
     * @return Annotation[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Annotation::all();
    }

    /**
     * Retrieve all annotations of a student.
     *
     * @param int $studentId
     *
     * @return Annotation[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedToStudent(int $studentId)
    {
        $annotations = Student::findOrFail($studentId)->annotations;

        if (count($annotations) === 0) {
            throw new NotFoundHttpException();
        }

        return $annotations;
    }

    /**
     * Retrieve an annotation.
     *
     * @param int $id
     *
     * @return Annotation
     */
    public function show(int $id)
    {
        return Annotation::findOrFail($id);
    }

}
