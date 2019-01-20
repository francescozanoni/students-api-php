<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Annotation;
use App\Models\Student;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AnnotationsController extends Controller
{

    /**
     * Retrieve all annotations.
     *
     * @return Annotation[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
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
    public function getRelatedToStudent(int $studentId) : Collection
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
    public function show(int $id) : Annotation
    {
        return Annotation::findOrFail($id);
    }

    /**
     * Create a student's annotations.
     *
     * @param Request $request
     * @param int $studentId
     *
     * @return Annotation
     */
    public function createRelatedToStudent(Request $request, int $studentId) : Annotation
    {
        $student = Student::findOrFail($studentId);

        $annotation = new Annotation($request->request->all());

        $student->annotations()->save($annotation);

        return $annotation;
    }

}
