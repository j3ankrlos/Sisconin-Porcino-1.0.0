<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function index()
    {
        return view('admin.animals.index');
    }

    public function create()
    {
        return view('admin.animals.create');
    }
}
