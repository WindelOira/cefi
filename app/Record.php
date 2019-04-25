<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'type', 'date', 'data'];

    /**
     * Get the patient that owns the record.
     */
    public function user() {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the record's data.
     *
     * @param  string  $value
     * @return string
     */
    public function getDataAttribute($value) {
        return unserialize($value);
    }

    /**
     * Set the record's data..
     *
     * @param  string  $value
     * @return void
     */
    public function setDataAttribute($value) {
        $this->attributes['data'] = serialize($value);
    }
}
