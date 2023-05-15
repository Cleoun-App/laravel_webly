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
        try {
            $req->validate([
                'email' => ['required', 'email', 'unique:users,email'],
                'name' => ['required', 'string', 'min:4', 'max:24'],
                'password' => ['required', 'min:4', 'max:50'],
            ]);

            $user = new User();

            $user->email = $req->email;
            $user->name = $req->name;
        } catch (\Throwable $th) {
            return redirect()->route('addUserPage');
        }
    }

    private function _addNewUser(array $data): \App\Models\User
    {
        return new User();
    }
}
