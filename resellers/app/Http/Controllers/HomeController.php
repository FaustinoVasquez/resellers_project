<?php

namespace App\Http\Controllers;

use App\Http\Requests;

class HomeController extends Controller
{
    public function index(){

        $passw = bcrypt('123456');
        return redirect('login');
    }
}
