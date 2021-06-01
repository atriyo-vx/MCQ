<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Questions;
use App\Models\Options;
use Redirect;

class MCQ extends Controller
{
    function index(){
        $data = Questions::all();
        return view('index',['data' => $data]);
    }
}
