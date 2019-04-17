<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Audit as AuditResource;
use App\Http\Resources\Student as StudentResource;
use App\Http\Resources\Traits\OptionalAuditsAttribute;
use App\Http\Resources\Traits\OptionalStudentAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class Eligibility extends JsonResource
{

    use OptionalStudentAttribute;
    use OptionalAuditsAttribute;

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

            'audits' => $this->when($this->withAuditsAttribute($request) === true, AuditResource::collection($this->audits)),

        ];

        return $output;

    }

}
