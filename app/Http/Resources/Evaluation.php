<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Internship as InternshipResource;
use App\Http\Resources\Traits\OptionalInternshipAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class Evaluation extends JsonResource
{

    use OptionalInternshipAttribute;

    public function toArray($request)
    {

        $output = [

            'id' => $this->id,
            'notes' => $this->notes,
            'internship' => $this->when($this->withInternshipAttribute($request) === true, new InternshipResource($this->internship)),

            // This field is returned as string, but cannot understand why...
            'clinical_tutor_id' => (int)$this->clinical_tutor_id,

        ];

        foreach (config('internships.evaluations.items') as $item) {
            $output[$item['name']] = $this->{$item['name']};
        }

        return $output;

    }

}
