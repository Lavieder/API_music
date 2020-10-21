<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\SearchWord;
use App\models\Song;
use App\models\Singer;
use App\models\Gedan;
use App\models\Album;
use App\models\User;

class SearchController extends Controller
{
  public function searchWord(Request $req)
  {
    $searchWord = $req->input('query');
    $select = Song::with(['album', 'singer'])
      ->whereHas(
        'album',
        function ($query) use ($searchWord) {
          $query->where('alName', 'like', '%' . $searchWord . '%');
        }
      )
      ->orWhereHas(
        'singer',
        function ($query) use ($searchWord) {
          $query->where('sgName', 'like', '%' . $searchWord . '%');
        }
      )
      ->orWhere('sName', 'like', '%' . $searchWord . '%')->take(10)->get(); //paginate(7);
    if (empty(count($select))) {
      return 0;
    }
    $hwName = SearchWord::where('swName', $searchWord)->get(['swid', 'swName']);
    if (empty(count($hwName))) {
      $insertWord = new SearchWord;
      $insertWord->hwName = $searchWord;
      $insertWord->save();
    }
    $scount = SearchWord::where('swName', $searchWord)->get('scount');
    foreach ($scount as $sc) {
      $sc = $sc->{"scount"};
    }
    SearchWord::where('swName', $searchWord)
      ->update(['scount' => ++$sc, 'updated_at' => now()]);
    $singer = Singer::where('sgName', $searchWord)->get();
    return json_encode([
      $select,
      'singer' => $singer
    ]);
  }

  public function HotWord()
  {
    $HotWords = SearchWord::OrderBy('scount', 'desc')->take(15)->get(['swid', 'swName']);
    return $HotWords;
  }

  public function allSuggest(Request $req)
  {
    $searchWord = $req->input('query');
    $song = Song::with(['album', 'singer'])
      ->whereHas(
        'album',
        function ($query) use ($searchWord) {
          $query->where('alName', 'like', '%' . $searchWord . '%');
        }
      )
      ->orWhereHas(
        'singer',
        function ($query) use ($searchWord) {
          $query->where('sgName', 'like', '%' . $searchWord . '%');
        }
      )
      ->orWhere('sName', 'like', '%' . $searchWord . '%')->paginate(20);
    $singer = SearchWord::where('swName', 'like', '%' . $searchWord . '%')->with('singer')->paginate(20);
    $album = Album::where('alName', 'like', '%' . $searchWord . '%')->with(['song', 'singer'])->paginate(20);
    $gedan = Gedan::where('gdTitle', 'like', '%' . $searchWord . '%')->with(['song', 'user'])->paginate(20);
    $user = User::where('nickName', 'like', '%' . $searchWord . '%')->paginate(20);
    return json_encode([
      'song' => $song,
      'singer' => $singer,
      'album' => $album,
      'gedan' => $gedan,
      'user' => $user
    ]);
  }
}
