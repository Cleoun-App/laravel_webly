<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;

class UserController extends Controller
{
    //


    public function addUser(Request $req)
    {
        return view('');
    }

    private function _addNewUser(array $data) : \App\Models\User
    {
        return new User();
    }
}
