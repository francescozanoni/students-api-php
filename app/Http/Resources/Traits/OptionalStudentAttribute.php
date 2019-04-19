<?php
declare(strict_types = 1);

namespace App\Http\Resources\Traits;

use Illuminate\Http\Request;

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
     * State whether the current model is to be appended with its related student model.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function withStudentAttribute(Request $request) : bool
    {
        return preg_match('/^(get|create)Student[A-Z][a-zA-Z]+$/', app('current_route_alias')) !== 1;
    }

}
