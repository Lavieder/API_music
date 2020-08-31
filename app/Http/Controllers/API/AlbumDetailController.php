<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Album;

class AlbumDetailController extends Controller
{
    public function albumDetail(Request $req)
    {
        $alid = $req->input('alid');
        $albumDetail = Album::where('alid',$alid)->with(['song','singer'])->get();
        return json_encode([
            'albumDetail' => $albumDetail
        ]);
    }
}
