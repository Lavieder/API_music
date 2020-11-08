<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class SearchWord extends Model
{
  protected $table = 'searchWord';
  protected $primaryKey = 'swid';
  public function song()
  {
    return $this->hasMany('App\Models\Song', 'swid');
  }

  public function singer()
  {
    return $this->belongsToMany('App\Models\Singer', 'song', 'swid', 'sgid');
  }
}
