<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Student as StudentResource;
use App\Http\Resources\Traits\OptionalStudentAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class Eligibility extends JsonResource
{

    use OptionalStudentAttribute;

    public function toArray($request)
    {

        $output = [

            'id' => $this->id,
            'notes' => $this->notes,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'student' => $this->when($this->withStudentAttribute($request) === true, new StudentResource($this->student)),

            // This field is returned as string, but cannot understand why...
            'is_eligible' => (boolean)$this->is_eligible,

            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,

        ];

        return $output;

    }

}
