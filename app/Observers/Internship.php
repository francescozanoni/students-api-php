<?php
declare(strict_types = 1);

namespace App\Observers;

use App\Models\Internship as InternshipModel;

class Internship
{

    /**
     * Handle the InternshipModel "deleted" event.
     *
     * @param  InternshipModel $internship
     */
    public function deleted(InternshipModel $internship)
    {
        $internship->evaluation()->delete();
        $internship->interruptionReport()->delete();
    }

}