<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

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
}
