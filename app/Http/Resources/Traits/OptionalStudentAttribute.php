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
        return preg_match('/^(get|create)Student[A-Z][a-zA-Z]+$/', app('current_route_alias')) !== 1;
    }

}
