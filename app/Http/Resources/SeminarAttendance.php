<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Student as StudentResource;
use App\Http\Resources\Traits\OptionalStudentAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class SeminarAttendance extends JsonResource
{

    use OptionalStudentAttribute;

    public function toArray($request)
    {

        $output = [

            'id' => $this->id,
            'seminar' => $this->seminar,
            'student' => $this->when($this->withStudentAttribute($request) === true, new StudentResource($this->student)),
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,

            // These field is returned as string, but cannot understand why...
            'credits' => (float)$this->credits,
        ];

        return $output;

    }

}
