<?php

namespace app\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserGedan;
use App\Models\Gedan;

class UserController extends Controller
{
    public function UserLogin(Request $req)
    {
        $res = ['isOk'=>'0'];
        $uName = $req->input('uName');
        $user = User::where('uName', $uName)->first();
        // 用户提交账号密码，正确返回1，错误返回0
        if($user && $user->pwd == md5($req->pwd)){
            // 账号，密码均正确就给用户发放一个令牌
            $tk = $user->create_token();
            $user = User::where('uName', $uName)->get(['uid','uName','nickName','faceUrl','sex','birth','area','intro','created_at']);
            $res = ['isOk'=>'1', 'tk'=>$tk, 'user' => $user];
        }
        return response()->json($res);
    }

    public function getUserGedan(Request $req)
    {
        $uid = $req->input('uid');
        $selectGd = User::where('uid', $uid)->with('gedan.song')->get(['uid','uName','nickName']);
        return $selectGd;
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
        $j = $insertUserGedan->save();
        if ($i == true && $j == true) {
            return 1;
        } else {
            return 0;
        }
    }
    
    // 更新个人信息
    public function updateUserInfo(Request $req)
    {
        $uName = $req->input('uName');
        $nickName = $req->input('nickName');
        $sex = $req->input('sex');
        $birth = $req->input('birth');
        $area = $req->input('area');
        $intro = $req->input('intro');
        $userInfo = [
            // 'nickName' => $nickName,
            'sex' => $sex
            // 'birth' => $birth,
            // 'area' => $area,
            // 'intro' => $intro
        ];
        $user = User::where('uName', $uName)->first();
        // return $user;
        if($user->update($userInfo)){
            return $user;
        }else{
            return '更新失败！';
        }
    }

    public function delGedan(Request $req)
    {
        $gdidArr = $req->input('gdid');
        $uid = $req->input('uid');
        // 删除用户的歌单
        $delGd = UserGedan::where('uid', $uid)->whereIn('gdid', $gdidArr)->delete();
        return $delGd;
    }
}
