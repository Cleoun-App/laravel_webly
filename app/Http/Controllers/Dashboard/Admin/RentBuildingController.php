<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Masters\Building;

class RentBuildingController extends Controller
{
    //

    public function rentBuilding(Request $request)
    {
        $data['page_title'] = "Sewa Gedung";
        $data['user'] = auth()->user();

        return view('dashboard.gedung.ds-admin-rent-building', $data);
    }

    public function transactions()
    {
        return view('dashboard.ds-add-user-page');
    }
}
