<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Audit as AuditResource;
use App\Http\Resources\Student as StudentResource;
use App\Http\Resources\Traits\OptionalAuditsAttribute;
use App\Http\Resources\Traits\OptionalStudentAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class OshCourseAttendance extends JsonResource
{

    use OptionalStudentAttribute;
    use OptionalAuditsAttribute;

    public function toArray($request)
    {

        $output = [

            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'student' => $this->when($this->withStudentAttribute($request) === true, new StudentResource($this->student)),
            'audits' => $this->when($this->withAuditsAttribute($request) === true, AuditResource::collection($this->audits)),

        ];

        return $output;

    }

}
