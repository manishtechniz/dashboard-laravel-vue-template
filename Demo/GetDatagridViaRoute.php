<?php
namespace App\Http\Controllers\Admin; 

use App\Http\Controllers\Admin\DataGrids\UserDataGrid;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
  public function index()
  {
    if (request()->ajax()) {
        return datagrid(UserDataGrid::class)->process();
    }

    return view('admin::users.index');
  }
 
}
