<?php
declare(strict_types = 1);

namespace App\Http\Resources\Traits;

use Illuminate\Http\Request;

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
     * State whether the current model is to be appended with its related internship model.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function withInternshipAttribute(Request $request) : bool
    {
        return preg_match('/^(get|create)Internship[A-Z][a-zA-Z]+$/', app('current_route_alias')) !== 1;
    }

}
