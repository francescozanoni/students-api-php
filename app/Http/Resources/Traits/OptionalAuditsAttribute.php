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
        return $request->has('with_audits') === true && $request->get('with_audits') === 'true';
    }

}
