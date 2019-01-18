<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Annotation;
use App\Models\Student;
use Illuminate\Http\Request;
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
     * @param int $id student ID
     *
     * @return Annotation[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedToStudent(int $id)
    {
        $annotations = Student::findOrFail($id)->annotations;

        if (count($annotations) === 0) {
            throw new NotFoundHttpException();
        }

        return $annotations;
    }

}
