<?php
declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BareInternship extends JsonResource
{

    public function toArray($request)
    {

        $output = [

            'id' => $this->id,
            'location' => $this->location->name,
            'sub_location' => $this->subLocation ? $this->subLocation->name : null,

            'start_date' => $this->start_date,
            'end_date' => $this->end_date,

            // These fields are returned as string, but cannot understand why...
            'hour_amount' => (int)$this->hour_amount,
            'other_amount' => (int)$this->other_amount,
            'is_optional' => (bool)$this->is_optional,
            'is_interrupted' => (bool)$this->is_interrupted,

        ];

        return $output;

    }

}
