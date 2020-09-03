<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Audit as AuditResource;
use App\Http\Resources\Traits\OptionalAuditsAttribute;
use App\Http\Resources\Traits\OptionalInternshipAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationWithoutInternship extends JsonResource
{

    use OptionalInternshipAttribute;
    use OptionalAuditsAttribute;

    public function toArray($request)
    {

        $output = [

            'id' => $this->id,
            'notes' => $this->notes,
            'audits' => $this->when($this->withAuditsAttribute($request) === true, AuditResource::collection($this->audits)),

        ];

        foreach (config('internships.evaluations.items') as $item) {
            $output[$item['name']] = $this->{$item['name']};
        }

        return $output;

    }

}
