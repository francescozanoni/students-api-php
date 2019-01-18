<?php
declare(strict_types = 1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Annotation extends JsonResource
{

    public function toArray($request)
    {
        return [

            'id' => $this->id,

            // This field is returned as string, but cannot understand why...
            'student_id' => (int)$this->student_id,

            'title' => $this->title,
            'content' => $this->content,

            // This field is returned as string, but cannot understand why...
            'user_id' => (int)$this->user_id,

            'created_at' => (string)$this->created_at,
            'updated_at' => (string)$this->updated_at,

        ];
    }

}
