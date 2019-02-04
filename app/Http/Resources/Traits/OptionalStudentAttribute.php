<?php
declare(strict_types = 1);

namespace App\Http\Resources\Traits;

/**
 * Trait OptionalStudentAttribute
 *
 * Inspired by https://github.com/laravel/framework/issues/1436
 *
 * @package App\Http\Resources\Traits
 */
trait OptionalStudentAttribute
{

    /**
     * @return bool
     */
    protected function withStudentAttribute($request) : bool
    {

        // @todo switch logic by reading URL first part to be "students/{id}/(something)"
        return in_array(
                app('current_route_alias'),
                ['getStudentStages', 'createStudentStage', 'getStudentAnnotations', 'createStudentAnnotation']
            ) === false;
    }

}
