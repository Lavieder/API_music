<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RankSong extends Model
{
    protected $table = 'ranksong';
    protected $primaryKey = 'rsid';

    public function Song()
    {
        return $this->belongsTo('App\Models\Song','sid');
    }

    public function RankBoard()
    {
        return $this->belongsTo('App\Models\RankBoard','rbid');
    }
}
