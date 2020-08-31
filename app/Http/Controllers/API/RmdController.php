<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Recommend;
use App\Models\Gedan;
use App\Models\Song;
use Illuminate\Support\Facades\Cache;

class RmdController extends Controller
{
    public function recBanner()
    {
        return Recommend::all();
    }
    
    public function recGedan()
    {   
        if (Cache::get('recGedanList') == '') {
            $recGedanList = Gedan::inRandomOrder()->take(5)->get();
            Cache::add('recGedanList', $recGedanList, now()->addHours(24));
        }
        return Cache::get('recGedanList');
    }

    public function recSong()
    {
        if (Cache::get('recSongList') == '') {
            $recSongList = Song::with(['album','singer'])->inRandomOrder()->take(6)->get();
            Cache::add('recSongList', $recSongList, now()->addHours(24));
        }
        return Cache::get('recSongList');
    }
}
