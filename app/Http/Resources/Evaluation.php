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
            
            // This field is returned as string, but cannot understand why...
            'clinical_tutor_id' => (int)$this->clinical_tutor_id,

            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,

        ];

        foreach (config('app.evaluations.items') as $item) {
            // @todo enable the following statement after updating evaluation schema within OpenAPI schema accordingly
            // $output[$item['name']] = $this->{$item['name']};
        }

        return $output;

    }

}
