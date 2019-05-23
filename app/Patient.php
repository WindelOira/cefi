<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Patient extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * Get the records for the patient.
     */
    public function records() {
        return $this->hasMany('App\Record');
    }

    /**
     * Get all of the meta datas for the user.
     */
    public function metas() {
        return $this->morphToMany('App\Meta', 'metaable');
    }
}
