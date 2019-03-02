<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\InterruptionReport;
use App\Models\Stage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InterruptionReportsController extends Controller
{

    /**
     * Retrieve all stage interruption reports.
     *
     * @return InterruptionReport[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
    {
        return InterruptionReport::all();
    }

    /**
     * Retrieve interruption report of a stage.
     *
     * @param int $stageId
     *
     * @return InterruptionReport
     */
    public function getRelatedToStage(int $stageId) : InterruptionReport
    {
        $interruptionReport = Stage::findOrFail($stageId)->interruptionReport;

        if (empty($interruptionReport) === true) {
            throw new NotFoundHttpException();
        }

        return $interruptionReport;
    }

    /**
     * Retrieve a stage interruption report.
     *
     * @param int $id
     *
     * @return InterruptionReport
     */
    public function show(int $id) : InterruptionReport
    {
        return InterruptionReport::findOrFail($id);
    }

    /**
     * Create a stage's interruption report.
     *
     * @param Request $request
     * @param int $stageId
     *
     * @return InterruptionReport
     */
    public function createRelatedToStage(Request $request, int $stageId) : InterruptionReport
    {
        $stage = Stage::findOrFail($stageId);

        $input = $request->request->all();

        $interruptionReport = new InterruptionReport($input);

        $stage->interruptionReport()->save($interruptionReport);

        return $interruptionReport;
    }

    /**
     * Modify a stage interruption report.
     *
     * @param Request $request
     * @param int $id
     *
     * @return InterruptionReport
     */
    public function update(Request $request, int $id) : InterruptionReport
    {
        $interruptionReport = InterruptionReport::findOrFail($id);

        $input = $request->request->all();

        $interruptionReport->fill($input);

        $interruptionReport->save();

        return $interruptionReport;
    }

    /**
     * Delete a stage interruption report.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $interruptionReport = InterruptionReport::findOrFail($id);
        $interruptionReport->delete();
    }

}
