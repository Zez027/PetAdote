<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function render(){
        return view('users.user'); 
    }

    public function getUserById(Request $request){
        $id = $request->id;

        return view('users.user')->with(['id' => $id]);
    }
}
