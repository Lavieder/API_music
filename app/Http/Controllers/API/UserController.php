<?php

namespace app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGedan;
use App\Models\Gedan;
use App\Models\FavouriteSong;
use Illuminate\Support\Str;

class UserController extends Controller
{
  // 登录
  public function UserLogin(Request $req)
  {
    $res = ['isOk' => '0'];
    $uName = $req->input('uName');
    $user = User::where('uName', $uName)->first();
    // 用户提交账号密码，正确返回1，错误返回0
    if ($user && $user->pwd == md5($req->pwd)) {
      // 账号，密码均正确就给用户发放一个令牌
      $tk = $user->create_token();
      $user = User::where('uName', $uName)->first(['uid', 'uName', 'nickName', 'sex', 'birth', 'faceUrl', 'area', 'intro']);
      $res = ['isOk' => '1', 'tk' => $tk, 'user' => $user];
    }
    return response()->json($res);
  }
  // 注册
  public function UserRegister(Request $req)
  {
    $uName = $req->input('uName');
    $pwd = $req->input('pwd');
    $user = User::where([['uName', $uName], ['pwd', md5($pwd)]])->first();
    if ($user == '') {
      $newUser = new User;
      $newUser->uName = $uName;
      $newUser->pwd = md5($pwd);
      $i = $newUser->save();
      return $i;
    } else {
      return 2;
    }
  }

  // 更新个人信息
  public function updateUserInfo(Request $req)
  {
    $uName = $req->input('uName');
    $initUser = User::where('uName', $uName)->first();
    $nickName = $req->input('nickName');
    $sex = $req->input('sex');
    $birth = $req->input('birth');
    $area = $req->input('area');
    $intro = $req->input('intro');
    if (empty($nickName)) {
      $nickName = $initUser->{'nickName'};
    }
    if (empty($sex)) {
      $sex = $initUser->{'sex'};
    }
    if (empty($birth)) {
      $birth = $initUser->{'birth'};
    }
    if (empty($area)) {
      $area = $initUser->{'area'};
    }
    if (empty($intro)) {
      $intro = $initUser->{'intro'};
    }
    $user = User::where('uName', $uName)
      ->update([
        'nickName' => $nickName,
        'sex' => $sex,
        'birth' => $birth,
        'area' => $area,
        'intro' => $intro
      ]);
    $afterUser = User::where('uName', $uName)->first(['uid', 'uName', 'nickName', 'sex', 'birth', 'faceUrl', 'area', 'intro']);
    $tk = $afterUser->create_token();
    $res = ['isOk' => '1', 'tk' => $tk, 'user' => $afterUser];
    if ($user && $afterUser) {
      return response()->json($res);
    } else {
      return '更新失败！';
    }
  }

  public function UpdateFace(Request $req) 
  {
    $uName = $req->input('uName');
    $user = User::where('uName', $uName)->first();
    $file = $req->file('file');
    if ($file->isValid()) {
      $ext = $file->getClientOriginalExtension();
      $filename = $uName.'M'.time().'.'.$ext;
      $path = public_path().'/storage/face/';
      $file->move($path, $filename);
      $faceUrl = 'face/'.$filename;
      $user = User::where('uName', $uName)
      ->update(['faceUrl' => $faceUrl]);
      $afterUser = User::where('uName', $uName)->first(['uid', 'uName', 'nickName', 'sex', 'birth', 'faceUrl', 'area', 'intro']);
      $tk = $afterUser->create_token();
      $res = ['isOk' => '1', 'tk' => $tk, 'user' => $afterUser];
      if ($user && $afterUser) {
        return response()->json($res);
      } else {
        return '更新失败！';
      }
      return $user;
    }
  }

  // 获取用户歌单
  public function getUserGedan(Request $req)
  {
    $uid = $req->input('uid');

    // 获取用户创建的歌单
    $createGd = User::where('uid', $uid)
      ->with(['gedan' => function ($query) {
        $query->where('source', '创建');
      }, 'gedan.song'])
      ->get(['uid', 'uName', 'nickName']);

    // 获取用户收藏的歌单
    $collectGd = User::where('uid', $uid)
      ->with(['gedan' => function ($query) {
        $query->where('source', '收藏');
      }, 'gedan.song'])
      ->get(['uid', 'uName', 'nickName']);
    return json_encode([
      'createGd' => $createGd,
      'collectGd' => $collectGd
    ]);
  }

  // 创建歌单
  public function createMyGedan(Request $req)
  {
    $gdTitle = $req->input('gdTitle');
    $uid = $req->input('uid');

    $insertGdTitle = new Gedan;
    $insertGdTitle->gdTitle = $gdTitle;
    $i = $insertGdTitle->save();

    $gdid = Gedan::where('gdTitle', $gdTitle)->orderBy('created_at', 'desc')->first('gdid')->gdid;
    $insertUserGedan = new UserGedan;
    $insertUserGedan->uid = $uid;
    $insertUserGedan->gdid = $gdid;
    $insertUserGedan->source = '创建';
    $j = $insertUserGedan->save();
    if ($i == true && $j == true) {
      return 1;
    } else {
      return 0;
    }
  }

  // 取消收藏收藏歌单
  public function collectGedan(Request $req)
  {
    $uid = $req->input('uid');
    $gdid = $req->input('gdid');
    $isCollect = $req->input('isCollect');
    if ($isCollect == 'true') {
      $insertUserGedan = new UserGedan;
      $insertUserGedan->uid = $uid;
      $insertUserGedan->gdid = $gdid;
      $insertUserGedan->source = '收藏';
      $msg = $insertUserGedan->save();
      $collectGd = User::where('uid', $uid)
        ->with(['gedan' => function ($query) {
          $query->where('source', '收藏');
        }, 'gedan.song'])
        ->get(['uid', 'uName', 'nickName']);
      return json_encode([
        $msg,
        $collectGd
      ]);
    } else {
      $source = '收藏';
      $delUserGd = UserGedan::where([['uid', $uid], ['gdid', $gdid], ['source', $source]])->delete();
      $collectGd = User::where('uid', $uid)
        ->with(['gedan' => function ($query) {
          $query->where('source', '收藏');
        }, 'gedan.song'])
        ->get(['uid', 'uName', 'nickName']);
      if ($delUserGd) {
        return json_encode([
          'reYes',
          $collectGd
        ]);
      } else {
        return 'reNo';
      }
    }
  }

  // 多选按钮删除歌单
  public function delGedan(Request $req)
  {
    $uid = $req->input('uid');
    $gdidArr = $req->input('gdid');
    $i = $req->input('source');
    $delGd = 0;
    if ($i == '0') {
      $source = '创建';
      $delGd = Gedan::whereIn('gdid', $gdidArr)->delete();
    } else if ($i == '1') {
      $source = '收藏';
    }
    // 删除用户创建/收藏的歌单
    $delUserGd = UserGedan::where([['uid', $uid], ['source', $source]])->whereIn('gdid', $gdidArr)->delete();
    if (($delUserGd && $delGd) || $delUserGd) {
      return '1';
    } else {
      return '0';
    }
  }

  // 添加/删除我喜欢的歌曲
  public function addReMyLikeSong(Request $req)
  {
    $sid = $req->input('sid');
    $uid = $req->input('uid');
    $isLike = $req->input('isLike');
    if ($isLike == 'true') {
      $myFavourite = new FavouriteSong;
      $myFavourite->uid = $uid;
      $myFavourite->sid = $sid;
      $myFavourite->save();
      $MyLikeSong = User::where('uid', $uid)->with(['song.singer', 'song.album'])->get(['uid', 'uName', 'nickName', 'faceUrl']);
      return json_encode([
        'add',
        $MyLikeSong
      ]);
    } else {
      $succ = FavouriteSong::where([['uid', $uid], ['sid', $sid]])->delete();
      $MyLikeSong = User::where('uid', $uid)->with(['song.singer', 'song.album'])->get(['uid', 'uName', 'nickName', 'faceUrl']);
      return json_encode([
        'remove',
        $MyLikeSong
      ]);
    }
  }

  // 获取用户喜欢的歌曲
  public function getMyLikeSong(Request $req)
  {
    $uid = $req->input('uid');
    $song = User::where('uid', $uid)->with(['song.singer', 'song.album'])->get(['uid', 'uName', 'nickName', 'faceUrl']);
    return $song;
  }

  // 删除用户喜欢的歌曲
  public function delMyLikeSong(Request $req)
  {
    $sids = $req->input('sids');
    $uid = $req->input('uid');
    $delLikeSong = FavouriteSong::where('uid', $uid)->whereIn('sid', $sids)->delete();
    return $delLikeSong;
  }
}
