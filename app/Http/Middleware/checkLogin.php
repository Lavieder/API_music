<?php

namespace App\Http\Middleware;

use Closure;
use App\models\User;

class checkLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $uName = $request->header('x-uName');
        $token = $request->header('x-token');
        // 验证用户名和令牌是否携带
        if(!empty($uName) && !empty($token)){
            // 验证是否合法(用户名，令牌)
            $user = User::where('uName', $uName)->first();
            if($user && $user->token == hash('sha256',$token)){
                return $next($request);
            }
        }
        $res = ['state' => 0, 'msg' => '未认证账号', 'errcode' => 401];
        return response()->json($res);
    }
}
