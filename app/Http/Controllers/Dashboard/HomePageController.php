<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomePageController extends Controller
{
    //


    public function index(Request $request)
    {
        try {

            $user = auth()->user();

            return view();
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
