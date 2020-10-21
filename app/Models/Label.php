<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    protected $table = 'label';
    protected $primaryKey = 'lbid';
    public function song () {
        return $this->belongsToMany('App\Models\song','songLabel','lbid','sid'); 
    }
}
