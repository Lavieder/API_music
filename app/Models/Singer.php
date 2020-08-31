<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Singer extends Model
{
    protected $table = 'singer';
    public $timestamps = false;
    protected $primaryKey = 'sgid';
    public function song()
    {
        return $this->hasMany('App\Models\song','sgid');
    }
    public function album()
    {
        return $this->belongsToMany('App\Models\Album','song','sgid','alid')->withPivot('sgid','alid');
    }
}
