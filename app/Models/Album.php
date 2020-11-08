<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
  protected $table = 'album';
  public $timestamps = false;
  protected $primaryKey = 'alid';
  public function song()
  {
    return $this->hasMany('App\Models\song', 'alid');
  }
  public function singer()
  {
    return $this->belongsToMany('App\Models\Singer', 'song', 'alid', 'sgid');
  }
}
