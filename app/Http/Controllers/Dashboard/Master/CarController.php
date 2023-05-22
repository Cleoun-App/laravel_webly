<?php

namespace App\Http\Controllers\Dashboard\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Masters\Car;

class CarController extends Controller
{
    public function addCarPage(Request $request)
    {
        try {

            $data['page_title'] = "Tambahkan Mobil";

            $data['user'] = auth()->user();
            $data['faker'] = fake('id');

            return view('dashboard.car.ds-master-add-car', $data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function addCar(Request $request)
    {
        try {

            $request->validate([
                'nama' => ['required', 'string', 'min:3', 'max:30'],
                'tipe' => ['required', 'string', 'min:3', 'max:120'],
                'km' => ['required', 'integer'],
                'plat_nomor' => ['required', 'string'],
                'no_stnk' => ['string'],
                'harga' => ['required', 'numeric'],
                'deskripsi' => ['required', 'string', 'min:3', 'max:350'],
            ]);

            $car = new Car();

            $car->name = $request->nama;
            $car->type = $request->tipe;
            $car->km = $request->km;
            $car->description = $request->deskripsi;
            $car->image = '';
            $car->slug =  uniqid(\Str::slug($request->nama));
            $car->stnk = $request->no_stnk;
            $car->license_plate = $request->plat_nomor;
            $car->price = $request->harga;

            $car->save();


            return redirect()->back()->with('success', 'Mobil berhasil di-tambahkan!');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function editCarPage($car_id)
    {
        $car = Car::find($car_id);

        $data['page_title'] = "Edit Data Mobil";

        $data['user'] = auth()->user();
        $data['car'] = $car;

        return view('dashboard.car.ds-master-edit-car', $data);
    }

    public function editCar(Request $request)
    {
        try {

            $request->validate([
                'nama' => ['required', 'string', 'min:3', 'max:30'],
                'tipe' => ['required', 'string', 'min:3', 'max:120'],
                'km' => ['required', 'integer'],
                'plat_nomor' => ['required', 'string'],
                'no_stnk' => ['string'],
                'harga' => ['required', 'numeric'],
                'deskripsi' => ['required', 'string', 'min:3', 'max:350'],
            ]);

            $car = Car::find($request->id);

            $car->name = $request->nama;
            $car->type = $request->tipe;
            $car->km = $request->km;
            $car->description = $request->deskripsi;
            $car->image = '';
            $car->slug =  uniqid(\Str::slug($request->nama));
            $car->stnk = $request->no_stnk;
            $car->license_plate = $request->plat_nomor;
            $car->price = $request->harga;

            $car->save();

            return redirect()->back()->with('success', 'Mobil berhasil di-update!');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function carTablePage(Request $request)
    {
        try {

            $data['page_title'] = "Tabel Mobil";

            $data['cars'] = Car::orderBy('created_at', 'DESC')->get();
            $data['user'] = auth()->user();

            return view('dashboard.car.ds-master-car-table', $data);
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function deleteCar($id)
    {
        try {

            $car = Car::find($id);

            $car->delete();

            return redirect()->back()->with('success', 'Mobil berhasil di hapus!!');
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
