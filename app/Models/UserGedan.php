<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class UserGedan extends Model
{
    protected $table = 'usergedan';
    protected $primaryKey = 'ugid';
    public $timestamps = false;
}
