<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class FavouriteSong extends Model
{
  protected $table = 'favouritesong';
  protected $primaryKey = 'fsid';
  public $timestamps = false;
}
