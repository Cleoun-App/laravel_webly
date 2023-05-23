<?php

namespace App\Http\Controllers\Dashboard\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Masters\Driver;

class DriverController extends Controller
{
    public function addDriverPage(Request $request)
    {
        try {

            $data['page_title'] = "Tambahkan Driver";

            $data['user'] = auth()->user();
            $data['faker'] = fake('id');

            return view('dashboard.driver.ds-master-add-driver', $data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function addDriver(Request $request)
    {
        try {

            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:30'],
                'license_type' => ['required', 'string', 'min:3', 'max:120'],
                'no_ktp' => ['required', 'numeric'],
                'nomor_ponsel' => ['required', 'numeric'],
                'address' => ['string'],
            ]);

            $driver = new Driver();

            $driver->photo = "";
            $driver->name = $request->name;
            $driver->license = $request->license_type;
            $driver->address = $request->address;
            $driver->phone_number = $request->nomor_ponsel;
            $driver->ktp = $request->no_ktp;
            $driver->slug =  uniqid(\Str::slug($request->nama));

            $driver->save();


            return redirect()->back()->with('success', 'Driver berhasil di-tambahkan!');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function editDriverPage($driver_id)
    {
        $driver = Driver::find($driver_id);

        $data['page_title'] = "Edit Data Driver";

        $data['user'] = auth()->user();
        $data['driver'] = $driver;

        return view('dashboard.driver.ds-master-edit-driver', $data);
    }

    public function editDriver(Request $request)
    {
        try {


            $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:30'],
                'license_type' => ['required', 'string', 'min:3', 'max:120'],
                'no_ktp' => ['required', 'numeric'],
                'nomor_ponsel' => ['required', 'numeric'],
                'address' => ['string'],
            ]);

            $driver = Driver::find($request->id);

            $driver->photo = "";
            $driver->name = $request->name;
            $driver->license = $request->license_type;
            $driver->address = $request->address;
            $driver->phone_number = $request->nomor_ponsel;
            $driver->ktp = $request->no_ktp;
            $driver->slug =  uniqid(\Str::slug($request->nama));

            $driver->save();

            return redirect()->back()->with('success', 'Driver berhasil di-update!');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function driverTablePage(Request $request)
    {
        try {

            $data['page_title'] = "Tabel Driver";

            $data['drivers'] = Driver::orderBy('created_at', 'DESC')->get();
            $data['user'] = auth()->user();

            return view('dashboard.driver.ds-master-driver-table', $data);
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function deleteDriver($id)
    {
        try {

            $driver = Driver::find($id);

            $driver->delete();

            return redirect()->back()->with('success', 'Driver berhasil di hapus!!');
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
