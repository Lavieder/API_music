<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Gedan extends Model
{
    protected $table = 'gedan';
    protected $primaryKey = 'gdid';
    public function user()
    {
        return $this->belongsTo('App\Models\User','uid');
    }
    public function song()
    {
        return $this->belongsToMany('App\Models\song','gedansong','gdid','sid');
    }
}
