<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class User extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'uid';
    public function gedan()
    {
        return $this->hasMany('App\Models\Gedan','uid');
    }

    public function create_token(){
        // 给令牌生成一个随机码，令牌变化越快越安全
        $tk = Str::random(128);
        // 对随机码进行运算生成token
        $this->token = hash('sha256', $tk);
        // 将运算后的数据存储到数据库
        $this->save();
        // 将token返回给用户，访问数据时随身携带
        return $tk;
    }
}
