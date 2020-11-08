<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recommend extends Model
{
  protected $table = 'recommend';
  public $timestamps = false;
  protected $primaryKey = 'rid';
}
