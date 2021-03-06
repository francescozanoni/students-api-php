<?php
declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use OwenIt\Auditing\Contracts\Auditable;

class InterruptionReport extends Model implements Auditable
{

    use \OwenIt\Auditing\Auditable;

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
    protected $guarded = ['id', 'internship_id'];

    /**
     * @return BelongsTo
     */
    public function internship() : BelongsTo
    {
        return $this->belongsTo('App\Models\Internship');
    }

}
