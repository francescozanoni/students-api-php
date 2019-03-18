<?php
declare(strict_types = 1);

namespace App\Http\Controllers;

use App\Models\InterruptionReport;
use App\Models\Internship;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InterruptionReportsController extends Controller
{

    /**
     * Retrieve all internship interruption reports.
     *
     * @return InterruptionReport[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index() : Collection
    {
        return InterruptionReport::all();
    }

    /**
     * Retrieve interruption report of a internship.
     *
     * @param int $internshipId
     *
     * @return InterruptionReport
     */
    public function getRelatedToInternship(int $internshipId) : InterruptionReport
    {
        $interruptionReport = Internship::findOrFail($internshipId)->interruptionReport;

        if (empty($interruptionReport) === true) {
            throw new NotFoundHttpException();
        }

        return $interruptionReport;
    }

    /**
     * Retrieve a internship interruption report.
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
     * Create a internship's interruption report.
     *
     * @param Request $request
     * @param int $internshipId
     *
     * @return InterruptionReport
     */
    public function createRelatedToInternship(Request $request, int $internshipId) : InterruptionReport
    {
        $internship = Internship::findOrFail($internshipId);

        $input = $request->request->all();

        $interruptionReport = new InterruptionReport($input);

        $internship->interruptionReport()->save($interruptionReport);

        return $interruptionReport;
    }

    /**
     * Modify a internship interruption report.
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
     * Delete a internship interruption report.
     *
     * @param int $id
     */
    public function destroy(int $id)
    {
        $interruptionReport = InterruptionReport::findOrFail($id);
        $interruptionReport->delete();
    }

}
