<?php
declare(strict_types = 1);

namespace App\Http\Resources\Traits;

/**
 * Trait OptionalAuditsAttribute
 *
 * Inspired by https://github.com/laravel/framework/issues/1436
 *
 * @package App\Http\Resources\Traits
 */
trait OptionalAuditsAttribute
{

    /**
     * @param $request
     * @return bool
     */
    protected function withAuditsAttribute($request) : bool
    {
        switch(get_class($this)) {
            case 'App\Http\Resources\Student':
                return $request->has('with_audits') === true && $request->get('with_audits') === 'true' &&
                    preg_match('/^(get|create)Student[A-Z][a-zA-Z]+$/', app('current_route_alias')) === 1;
            case 'App\Http\Resources\Internship':
                return $request->has('with_audits') === true && $request->get('with_audits') === 'true' &&
                    preg_match('/^(get|create)Internship[A-Z][a-zA-Z]+$/', app('current_route_alias')) === 1;
            default:
                return $request->has('with_audits') === true && $request->get('with_audits') === 'true';
        }
    }

}
