<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Audit extends JsonResource
{

    public function toArray($request)
    {

        $output = [
            'event' => $this->event,
            'old_values' => $this->old_values,
            'new_values' => $this->new_values,
            'user_id' => (int)$this->user_id,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];

        return $output;

    }

}
