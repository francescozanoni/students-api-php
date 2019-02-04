<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Student as StudentResource;
use App\Http\Resources\Traits\OptionalStudentAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class Stage extends JsonResource
{

    use OptionalStudentAttribute;

    public function toArray($request)
    {

        $output = [

            'id' => $this->id,
            'location' => $this->location->name,
            'sub_location' => $this->subLocation ? $this->subLocation->name : null,
            'student' => $this->when($this->withStudentAttribute($request) === true, new StudentResource($this->student)),

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
