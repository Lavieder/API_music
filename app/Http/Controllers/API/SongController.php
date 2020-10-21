<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class SongController extends Controller
{
    public function todaySong()
    {
      $todayDate = date('Y-m-d');
      $todaylist = Song::with(['album','singer'])->inRandomOrder()->take(15)->get();
      if ($todayDate !== Cache::get('todayDate')) {
        Cache::forget('todayDate');
        Cache::add('todayDate',$todayDate);
        Cache::forget('todaylist');
        Cache::add('todaylist', $todaylist);
      }
      return Cache::get('todaylist');
    }

    // 增加歌曲播放量
    public function increPlayNum(Request $req) 
    {
      $sid = $req->input('sid');
      $song = Song::find($sid);
      $playNum = $song->playNum;
      $song->playNum = ++$playNum;
      $playNum = $song->save();
      if ($playNum) {
        return '1';
      } else {
        return '0';
      }
    }
   
}
