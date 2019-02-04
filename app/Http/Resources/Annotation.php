<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use App\Http\Resources\Student as StudentResource;
use App\Http\Resources\Traits\OptionalStudentAttribute;
use Illuminate\Http\Resources\Json\JsonResource;

class Annotation extends JsonResource
{

    use OptionalStudentAttribute;

    public function toArray($request)
    {

        $output = [

            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'student' => $this->when($this->withStudentAttribute($request) === true, new StudentResource($this->student)),

            // This field is returned as string, but cannot understand why...
            'user_id' => (int)$this->user_id,

            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,

        ];

        return $output;

    }

}
