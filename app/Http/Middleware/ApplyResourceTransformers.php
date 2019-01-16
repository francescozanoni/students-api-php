<?php
declare(strict_types = 1);

namespace App\Http\Middleware;

use App\Http\Resources\Student as StudentResource;
use App\Models\Student;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class ApplyTransformers
 * @package App\Http\Middleware
 */
class ApplyResourceTransformers
{

    /**
     * Add response metadata
     *
     * @param $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, \Closure $next)
    {

        $response = $next($request);

        switch (app('current_route_alias')) {

            case 'getStudents':
                // Since response contains data as JSON, data must be first converted
                // to a collection of Eloquent models, which can be used as input of resource collection transformer.
                $data = $this->jsonToModelCollection($response->original, Student::class);
                $response->setContent(StudentResource::collection($data));
                break;

            case 'createStudent':
            case 'getStudentById':
            case 'updateStudentById':
                // Since response contains data as JSON, data must be first converted
                // to an Eloquent model, which can be used as input of resource transformer.
                $data = $this->jsonToModel($response->original, Student::class);
                $response->setContent(new StudentResource($data));
                break;

            case 'deleteStudentById':
                break;

            default:

        }

        return $response;

    }

    /**
     * Convert a JSON array to a collection of Eloquent models.
     *
     * @param string $json
     * @param string $modelClass
     *
     * @return \Illuminate\Support\Collection
     */
    private function jsonToModelCollection(string $json, string $modelClass) : Collection
    {

        $data = json_decode($json, true);

        $data = array_map(function ($datum) use ($modelClass) {
            $model = new $modelClass();
            $model->forceFill($datum);
            return $model;
        }, $data);
        $collection = collect($data);

        return $collection;

    }

    /**
     * Convert a JSON object to an Eloquent model.
     *
     * @param string $json
     * @param string $modelClass
     *
     * @return Model
     */
    private function jsonToModel(string $json, string $modelClass) : Model
    {

        $data = json_decode($json, true);

        $model = new $modelClass();
        $model->forceFill($data);

        return $model;

    }

}