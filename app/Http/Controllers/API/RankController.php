<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\RankBoard;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RankController extends Controller
{
    public function rankBoard() {
        $todayDate = date('Y-m-d');
        $tuijianList = RankBoard::whereNotIn('rbid', [1,2,3,4])->inRandomOrder()->take(3)->get();
        if ($todayDate !== Cache::get('todayDate')) {
            Cache::forget('todayDate');
            Cache::add('todayDate',$todayDate);
            Cache::forget('tuijianList');
            Cache::add('tuijianList', $tuijianList);
        }
        $peakList = RankBoard::whereIn('rbid', [1,2,3,4])->get();
        
        $tjArr = Array();
        foreach (Cache::get('tuijianList') as $tj) {
            $tjArr[] = $tj->{'rbid'};
        }
        $otherList = RankBoard::whereNotIn('rbid', [1,2,3,4,$tjArr[0],$tjArr[1],$tjArr[2]])->take(6)->get();
        return json_encode([
            'tuijian' => Cache::get('tuijianList'),
            'peak' => $peakList,
            'other' => $otherList
        ]);
        // $week = Carbon::create($todayDate)->dayOfWeek;
    }

    public function rankDetail(Request $req)
    {
        $rbid = $req->input('rbid');
        $rankDetail = RankBoard::where('rbid', $rbid)->with(['song','song.album', 'song.singer'])->get();
        return $rankDetail;
    }
}
