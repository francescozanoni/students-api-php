<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Audit as AuditResource;
use App\Http\Resources\Traits\OptionalAuditsAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class Student extends JsonResource
{

    use OptionalAuditsAttribute;

    public function toArray($request)
    {

        return [

            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'e_mail' => $this->e_mail,
            'phone' => $this->phone,
            'nationality' => $this->nationality,
            'audits' => $this->when($this->withAuditsAttribute($request) === true, AuditResource::collection($this->audits)),

        ];

    }

}
