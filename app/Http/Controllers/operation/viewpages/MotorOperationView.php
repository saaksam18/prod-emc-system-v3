<?php

namespace App\Http\Controllers\operation\viewpages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MotorOperationView extends Controller
{
  public function index()
  {
    return view('layouts.sections.operation.viewPages.motorbike-operation-view');
  }
}
