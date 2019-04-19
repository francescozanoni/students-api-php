<?php
declare(strict_types = 1);

namespace App\Http\Resources\Traits;

use Illuminate\Http\Request;

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
     * State whether the current model is to be appended with its audits.
     *
     * @param Request $request
     *
     * @return bool
     */
    protected function withAuditsAttribute(Request $request) : bool
    {

        // @todo within a middleware, if "with_audits" is not provided, populate request with the default value
        if ($request->has('with_audits') === false ||
            $request->get('with_audits') !== 'true') {
            return false;
        }

        switch (get_class($this)) {

            case 'App\Http\Resources\Student':
                return in_array(app('current_route_alias'), ['getStudents', 'getStudentById']) === true;

            case 'App\Http\Resources\Internship':
                return in_array(app('current_route_alias'), ['getInternships', 'getStudentInternships', 'getInternshipById']) === true;

            default:
                return true;

        }

    }

}
