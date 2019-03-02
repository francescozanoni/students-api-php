<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Stage as StageResource;
use App\Http\Resources\Traits\OptionalStageAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class Evaluation extends JsonResource
{

    use OptionalStageAttribute;

    public function toArray($request)
    {

        $output = [

            'id' => $this->id,
            'notes' => $this->notes,
            'stage' => $this->when($this->withStageAttribute($request) === true, new StageResource($this->stage)),

        ];

        return $output;

    }

}
