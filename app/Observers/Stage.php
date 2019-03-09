<?php
declare(strict_types = 1);

namespace App\Observers;

use App\Models\Stage as StageModel;

class Stage
{

    /**
     * Handle the StageModel "deleted" event.
     *
     * @param  StageModel $stage
     */
    public function deleted(StageModel $stage)
    {
        $stage->evaluation()->delete();
        $stage->interruptionReport()->delete();
    }

}