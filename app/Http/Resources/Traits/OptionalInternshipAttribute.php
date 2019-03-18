<?php
declare(strict_types = 1);

namespace App\Http\Resources\Traits;

/**
 * Trait OptionalInternshipAttribute
 *
 * Inspired by https://github.com/laravel/framework/issues/1436
 *
 * @package App\Http\Resources\Traits
 */
trait OptionalInternshipAttribute
{

    /**
     * @return bool
     */
    protected function withInternshipAttribute($request) : bool
    {
        return preg_match('/^(get|create)Internship[A-Z][a-zA-Z]+$/', app('current_route_alias')) !== 1;
    }

}
