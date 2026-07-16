<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

class AdminRoleController extends Controller
{
  public function index()
  {
    return view('admin::roles.index');
  }
}
