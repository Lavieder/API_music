<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\RankBoard;
use App\models\Label;
use App\models\Song;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class RankController extends Controller
{
  public function rankBoard()
  {
    $todayDate = date('Y-m-d');
    $tuijianList = RankBoard::whereNotIn('rbid', [1, 2, 3, 4])->inRandomOrder()->take(3)->get();
    if ($todayDate !== Cache::get('todayDate')) {
      Cache::forget('todayDate');
      Cache::add('todayDate', $todayDate);
      Cache::forget('tuijianList');
      Cache::add('tuijianList', $tuijianList);
    }
    $peakList = RankBoard::whereIn('rbid', [1, 2, 3, 4])->get();

    $tjArr = array();
    foreach (Cache::get('tuijianList') as $tj) {
      $tjArr[] = $tj->{'rbid'};
    }
    $otherList = RankBoard::whereNotIn('rbid', [1, 2, 3, 4, $tjArr[0], $tjArr[1], $tjArr[2]])->take(6)->get();
    return json_encode([
      'tuijian' => Cache::get('tuijianList'),
      'peak' => $peakList,
      'other' => $otherList
    ]);
  }

  public function rankDetail(Request $req)
  {
    $rbid = $req->input('rbid');
    if ($rbid == 1 || $rbid == 12 || $rbid == 13) {
      $rankDetail = Song::with(['singer', 'album'])->inRandomOrder()->take(3)->get();
    } else if ($rbid == 2) {
      $rankDetail = Song::with(['singer', 'album'])->orderBy('created_at', 'desc')->take(3)->get();
    } else if ($rbid == 3) {
      $rankDetail = Label::where("lbid", 1)->with(['song', 'song.singer', 'song.album'])->take(3)->get();
    } else if ($rbid == 4) {
      $rankDetail = Song::with(['singer', 'album'])->orderBy('playNum', 'desc')->take(3)->get();
    } else if ($rbid == 11) {
      $rankDetail = Label::where("lbid", 9)->with(['song' => function ($query) {
        $query->orderBy('playNum', 'desc');
      }, 'song.singer', 'song.album'])->take(3)->get();
    } else {
      if ($rbid == 5) {
        $lbid = 2;
      } else if ($rbid == 6) {
        $lbid = 3;
      } else if ($rbid == 7) {
        $lbid = 4;
      } else if ($rbid == 8) {
        $lbid = 5;
      } else if ($rbid == 9) {
        $lbid = 6;
      } else if ($rbid == 10) {
        $lbid = 7;
      } else {
        return "没有此榜单";
      }
      $rankDetail = Label::where("lbid", $lbid)->with(['song', 'song.singer', 'song.album'])->take(3)->get();
    }
    return $rankDetail;
  }

  public function rankSong()
  {
    $todayDate = date('Y-m-d');
    $soarSong = Song::with(['singer:sgid,sgName'])
      ->orderBy('playNum', 'desc')->take(3)->get();
    $newSong = Song::with(['singer:sgid,sgName'])
      ->orderBy('created_at', 'desc')->take(3)->get();
    // 获取一周的某个星期几
    $week = Carbon::create($todayDate)->dayOfWeek;
    if ($week == '4') {
      $initSong = Song::where('subName', '=', null)->with(['singer:sgid,sgName'])->inRandomOrder()->take(3)->get();
      $hotSong = Song::orderBy('playNum', 'desc')->with(['singer:sgid,sgName'])->take(3)->get();
      Cache::forget('initSong');
      Cache::forget('hotSong');
      Cache::add('initSong', $initSong);
      Cache::add('hotSong', $hotSong);
    } else {
      $initSong = Cache::get('initSong');
      $hotSong = Cache::get('hotSong');
    }
    return json_encode([
      'soarSong' => $soarSong,
      'newSong' => $newSong,
      'initSong' => $initSong,
      'hotSong' => $hotSong
    ]);
  }
}
