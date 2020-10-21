<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankBoard extends Model
{
    protected $table = 'rankBoard';
    protected $primaryKey = 'rbid';
    public function song()
    {
        return $this->belongsToMany('App\Models\Song','RankSong','rbid','sid');
    }
}
