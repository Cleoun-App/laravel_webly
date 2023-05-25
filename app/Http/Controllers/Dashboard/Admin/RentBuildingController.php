<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RentBuildingController extends Controller
{
    //

    public function rentBuilding(Request $request)
    {
        $data['page_title'] = "Sewa Gedung";
        $data['user'] = auth()->user();

        return view('dashboard.gedung.ds-admin-rent-building', $data);
    }
}
