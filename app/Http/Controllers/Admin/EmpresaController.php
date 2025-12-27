<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmpresaController extends Controller
{
    public function index()
    {
        return view('admin.empresa.index');
    }

    public function granjas()
    {
        return view('admin.granjas.index');
    }

    public function especies()
    {
        return view('admin.especies.index');
    }
}
