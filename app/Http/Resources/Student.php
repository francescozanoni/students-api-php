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
            'text_field_a' => $this->text_field_a,
            'text_field_b' => $this->text_field_b,
            'date_field_a' => $this->date_field_a,
            'date_field_b' => $this->date_field_b,
            'amount_field_a' => $this->when($this->amount_field_a !== null, (int)$this->amount_field_a),
            'amount_field_b' => $this->when($this->amount_field_b !== null, (int)$this->amount_field_b),
            'audits' => $this->when($this->withAuditsAttribute($request) === true, AuditResource::collection($this->audits)),

        ];

    }

}
