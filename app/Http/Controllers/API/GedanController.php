<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Gedan;


class GedanController extends Controller
{
  public function gedanAll()
  {
    $gedanlist = Gedan::where('gdPlayNum', '>=', '2000')->take(15)->get();
    return  $gedanlist;
  }

  public function gedanDetail(Request $req)
  {
    $gdid = $req->input('gdid');
    $gedanDetaillist = Gedan::where('gdid', $gdid)->with(['user', 'song.album', 'song.singer'])->get();
    return $gedanDetaillist;
  }
}
