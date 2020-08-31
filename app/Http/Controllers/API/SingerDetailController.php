<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Singer;
use App\Models\Album;

use function GuzzleHttp\json_decode;

class singerDetailController extends Controller
{
    public function singerDetail(Request $req)
    {
        $sgid = $req->input('sgid');
        $detail = Singer::where('sgid',$sgid)->with(['song.album','song.singer'])->get();
        $album = Album::where('sgid',$sgid)->with(['singer','song'])->get();
        return json_encode([
            'album' => $album,
            'detail' => $detail
        ]);
    }

    public function getLyrics(Request $req) 
    {
        $sid = $req->input('sid');
        $sList = DB::select('select * from song where sid=?',[$sid]);
        $jsonen = json_encode($sList);
        $json = json_decode($jsonen,true);
        $file_path = $_SERVER["DOCUMENT_ROOT"].'/storage/'.$json[0]["lyricsUrl"];
        $hand = fopen($file_path,'r');
        while(!feof($hand))
        {
            echo fgets($hand);//fgets()函数从文件指针中读取一行
        }
        fclose($hand);
    }
}