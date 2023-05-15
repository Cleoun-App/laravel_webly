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


            $data['user'] = $user;
            $data['page_title'] = "Halam Utama";

            return view('dashboard.ds-index-page', $data);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
