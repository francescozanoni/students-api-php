<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Student as StudentResource;

class Annotation extends JsonResource
{

    public function toArray($request)
    {

        $withStudentProperty = in_array(app('current_route_alias'), ['getStudentAnnotations', 'createStudentAnnotation']) === false;

        $output = [

            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'student' => $this->when($withStudentProperty === true, new StudentResource($this->student)),

            // This field is returned as string, but cannot understand why...
            'user_id' => (int)$this->user_id,

            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,

        ];

        return $output;

    }

}
