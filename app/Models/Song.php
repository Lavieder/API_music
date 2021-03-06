<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class song extends Model
{
  protected $table = 'song';
  public $timestamps = false;
  protected $primaryKey = 'sid';
  public function album()
  {
    return $this->belongsTo('App\Models\album', 'alid');
  }
  public function singer()
  {
    return $this->belongsTo('App\Models\singer', 'sgid');
  }
  public function gedan()
  {
    return $this->belongsToMany('App\Models\gedan', 'gedansong', 'sid', 'gdid');
  }

  public function RankSong()
  {
    return $this->hasMany('App\Models\RankSong', 'sid');
  }
  public function label()
  {
    return $this->belongsToMany('App\Models\Label', 'songLabel', 'sid', 'lbid');
  }
}
