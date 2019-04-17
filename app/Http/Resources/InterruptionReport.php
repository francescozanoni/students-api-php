<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Audit as AuditResource;
use App\Http\Resources\Internship as InternshipResource;
use App\Http\Resources\Traits\OptionalAuditsAttribute;
use App\Http\Resources\Traits\OptionalInternshipAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class InterruptionReport extends JsonResource
{

    use OptionalInternshipAttribute;
    use OptionalAuditsAttribute;

    public function toArray($request)
    {

        $output = [

            'id' => $this->id,
            'notes' => $this->notes,
            'internship' => $this->when($this->withInternshipAttribute($request) === true, new InternshipResource($this->internship)),
            'audits' => $this->when($this->withAuditsAttribute($request) === true, AuditResource::collection($this->audits)),

        ];

        return $output;

    }

}
