<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EvaluationsController extends Controller
{

    /**
     * Retrieve all stage evaluations.
     *
     * @return Evaluation[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
    {
        return Evaluation::all();
    }

    /**
     * Retrieve evaluation of a stage.
     *
     * @param int $stageId
     *
     * @return Evaluation
     */
    public function getRelatedToStage(int $stageId) : Evaluation
    {
        $evaluation = Stage::findOrFail($stageId)->evaluation;

        if (empty($evaluation) === true) {
            throw new NotFoundHttpException();
        }

        return $evaluation;
    }

    /**
     * Retrieve a stage evaluation.
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
     * Create a stage's evaluation.
     *
     * @param Request $request
     * @param int $stageId
     *
     * @return Evaluation
     */
    public function createRelatedToStage(Request $request, int $stageId) : Evaluation
    {
        $stage = Stage::findOrFail($stageId);

        $input = $request->request->all();

        $evaluation = new Evaluation($input);

        $stage->evaluation()->save($evaluation);

        return $evaluation;
    }

    /**
     * Modify a stage evaluation.
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

        $evaluation->fill($input);

        $evaluation->save();

        return $evaluation;
    }

    /**
     * Delete a stage evaluation.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $evaluation = Evaluation::findOrFail($id);
        $evaluation->delete();
    }

}
