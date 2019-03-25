<?php
declare(strict_types = 1);

namespace App\Models;

use App\Models\Traits\EloquentGetTableName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{

    use SoftDeletes;
    use EloquentGetTableName;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    /**
     * @return HasMany
     */
    public function annotations() : HasMany
    {
        return $this->hasMany('App\Models\Annotation');
    }

    /**
     * @return HasMany
     */
    public function internships() : HasMany
    {
        return $this->hasMany('App\Models\Internship');
    }

    /**
     * @return HasMany
     */
    public function educationalActivityAttendances() : HasMany
    {
        return $this->hasMany('App\Models\EducationalActivityAttendance');
    }

    /**
     * @return HasMany
     */
    public function eligibilities() : HasMany
    {
        return $this->hasMany('App\Models\Eligibility');
    }

    /**
     * @return HasMany
     */
    public function oshCourseAttendances() : HasMany
    {
        return $this->hasMany('App\Models\OshCourseAttendance');
    }

}
