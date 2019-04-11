<?php
declare(strict_types = 1);

namespace App\Models;

use App\Models\Traits\EloquentGetTableName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{

    use EloquentGetTableName;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

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
