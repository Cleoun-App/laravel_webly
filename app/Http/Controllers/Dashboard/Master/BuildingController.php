<?php

namespace App\Http\Controllers\Dashboard\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Masters\Building;

class BuildingController extends Controller
{
    public function addBuildingPage(Request $request)
    {
        try {

            $data['page_title'] = "Tambahkan Gedung";

            $data['user'] = auth()->user();
            $data['faker'] = fake('id');

            return view('dashboard.gedung.ds-master-add-gedung', $data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function addBuilding(Request $request)
    {
        try {

            $request->validate([
                'nama' => ['required', 'string', 'min:3', 'max:100'],
                'lokasi' => ['required', 'string', 'min:5', 'max:120'],
                'kapasitas' => ['required', 'integer', 'min:1'],
                'tipe' => ['required', 'min:3', 'max:120'],
                'harga' => ['required', 'numeric'],
                'deskripsi' => ['required', 'string', 'min:3', 'max:350'],
            ]);

            $building = new Building();

            $building->name = $request->nama;
            $building->price = $request->harga;
            $building->capacity = $request->kapasitas;
            $building->location = $request->lokasi;
            $building->description = $request->deskripsi;
            $building->image = '';
            $building->slug = \Str::slug($request->nama);
            $building->category = $request->tipe;

            $building->save();

            return redirect()->back()->with('success', 'Gedung berhasil di-tambahkan!');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function editBuildingPage($building_id)
    {
        $building = Building::find($building_id);

        $data['page_title'] = "Edit Data Gedung";

        $data['user'] = auth()->user();
        $data['building'] = $building;

        return view('dashboard.gedung.ds-master-edit-gedung', $data);
    }

    public function editBuilding(Request $request)
    {
        try {

            $request->validate([
                'nama' => ['required', 'string', 'min:3', 'max:100'],
                'lokasi' => ['required', 'string', 'min:5', 'max:120'],
                'kapasitas' => ['required', 'integer', 'min:1'],
                'tipe' => ['required', 'min:3', 'max:120'],
                'harga' => ['required', 'numeric'],
                'deskripsi' => ['required', 'string', 'min:3', 'max:350'],
            ]);

            $building = Building::find($request->id);

            $building->name = $request->nama;
            $building->price = $request->harga;
            $building->capacity = $request->kapasitas;
            $building->location = $request->lokasi;
            $building->description = $request->deskripsi;
            $building->image = '';
            $building->slug = \Str::slug($request->nama);
            $building->category = $request->tipe;

            $building->save();

            return redirect()->back()->with('success', 'Gedung berhasil di-update!');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function buildingTablePage(Request $request)
    {
        try {

            $data['page_title'] = "Tabel Gedung";

            $data['buildings'] = Building::orderBy('created_at', 'DESC')->get();
            $data['user'] = auth()->user();

            return view('dashboard.gedung.ds-master-building-table', $data);
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function deleteBuilding($id)
    {
        try {

            $building = Building::find($id);

            $building->delete();

            return redirect()->back()->with('success', 'Gedung berhasil di hapus!!');
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
