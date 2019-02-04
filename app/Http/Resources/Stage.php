<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Student as StudentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class Stage extends JsonResource
{

    public function toArray($request)
    {

        $withStudentProperty = in_array(app('current_route_alias'), ['getStudentStages', 'createStudentStage']) === false;

        $output = [

            'id' => $this->id,
            'location' => $this->location->name,
            'sub_location' => $this->subLocation ? $this->subLocation->name : null,
            'student' => $this->when($withStudentProperty === true, new StudentResource($this->student)),

            'start_date' => $this->start_date,
            'end_date' => $this->end_date,

            // These fields is returned as string, but cannot understand why...
            'hour_amount' => (int)$this->hour_amount,
            'other_amount' => (int)$this->other_amount,
            'is_optional' => (bool)$this->is_optional,
            'is_interrupted' => (bool)$this->is_interrupted,

        ];

        return $output;

    }

}
