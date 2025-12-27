<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $userCount = User::count();
        $animalCount = \App\Models\Animal::count();
        $especieCount = \App\Models\Especie::count();
        $razaCount = \App\Models\Raza::count();
        $granjaCount = \App\Models\Granja::count();

        return view('admin.index', compact(
            'userCount', 
            'animalCount', 
            'especieCount', 
            'razaCount', 
            'granjaCount'
        ));
    }
}