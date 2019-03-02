<?php
declare(strict_types = 1);

namespace App\Http\Resources\Traits;

/**
 * Trait OptionalStageAttribute
 *
 * Inspired by https://github.com/laravel/framework/issues/1436
 *
 * @package App\Http\Resources\Traits
 */
trait OptionalStageAttribute
{

    /**
     * @return bool
     */
    protected function withStageAttribute($request) : bool
    {
        return preg_match('/^(get|create)Stage[A-Z][a-zA-Z]+$/', app('current_route_alias')) !== 1;
    }

}
