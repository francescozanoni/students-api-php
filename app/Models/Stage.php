<?php
declare(strict_types = 1);

namespace App\Models;

use App\Models\Traits\EloquentGetTableName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Stage extends Model
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
     * @return BelongsTo
     */
    public function student() : BelongsTo
    {
        return $this->belongsTo('App\Models\Student');
    }

    /**
     * @return BelongsTo
     */
    public function location() : BelongsTo
    {
        return $this->belongsTo('App\Models\Location');
    }

    /**
     * @return BelongsTo
     */
    public function subLocation() : BelongsTo
    {
        return $this->belongsTo('App\Models\SubLocation');
    }

}
