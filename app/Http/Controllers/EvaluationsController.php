<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Internship;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EvaluationsController extends Controller
{

    /**
     * Retrieve all internship evaluations.
     *
     * @return Evaluation[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
    {
        return Evaluation::all();
    }

    /**
     * Retrieve evaluation of an internship.
     *
     * @param int $internshipId
     *
     * @return Evaluation
     */
    public function getRelatedToInternship(int $internshipId) : Evaluation
    {
        $evaluation = Internship::findOrFail($internshipId)->evaluation;

        if (empty($evaluation) === true) {
            throw new NotFoundHttpException();
        }

        return $evaluation;
    }

    /**
     * Retrieve an internship evaluation.
     *
     * @param int $id
     *
     * @return Evaluation
     */
    public function show(int $id) : Evaluation
    {
        return Evaluation::findOrFail($id);
    }

    /**
     * Create an internship's evaluation.
     *
     * @param Request $request
     * @param int $internshipId
     *
     * @return Evaluation
     */
    public function createRelatedToInternship(Request $request, int $internshipId) : Evaluation
    {
        $internship = Internship::findOrFail($internshipId);

        $input = $request->request->all();

        $evaluation = new Evaluation($input);

        $internship->evaluation()->save($evaluation);

        return $evaluation;
    }

    /**
     * Modify an internship evaluation.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Evaluation
     */
    public function update(Request $request, int $id) : Evaluation
    {
        $evaluation = Evaluation::findOrFail($id);

        $input = $request->request->all();

        // "notes" is an optional field: in case it's removed (not available within input),
        // it must be set to null, otherwise the change is ignored by $evaluation->fill($input).
        if (array_key_exists('notes', $input) === false) {
            $input['notes'] = null;
        }

        $evaluation->fill($input);

        $evaluation->save();

        return $evaluation;
    }

    /**
     * Delete an internship evaluation.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $evaluation = Evaluation::findOrFail($id);
        $evaluation->delete();
    }

}
