<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Meta extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['key', 'val'];

    /**
     * Get all of the users that are assigned this meta data.
     */
    public function users() {
        return $this->morphedByMany('App\User', 'metaable');
    }

    /**
     * Get all of the patients that are assigned this meta data.
     */
    public function patients() {
        return $this->morphedByMany('App\Patient', 'metaable');
    }
}
