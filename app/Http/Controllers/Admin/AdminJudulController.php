<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminJudulController extends Controller
{
    public function viewJudul() 
    {
        return view('admin.judul.index');
    }
}
