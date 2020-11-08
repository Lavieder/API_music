<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Singer;
use App\Models\AreaClass;
use App\Models\SexClass;

class SingerController extends Controller
{
  public function singerAll()
  {
    return Singer::paginate(15);
  }

  public function areaClassAll()
  {
    return AreaClass::all();
  }

  public function sexClassAll()
  {
    return SexClass::all();
  }
}
