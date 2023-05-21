<?php

namespace App\Http\Controllers\Dashboard\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\Masters\Canteen;

class CanteenController extends Controller
{
    public function addCanteenPage(Request $request)
    {
        try {

            $data['page_title'] = "Tambahkan Kantin";

            $data['user'] = auth()->user();
            $data['faker'] = fake('id');

            return view('dashboard.canteen.ds-master-add-canteen', $data);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function addCanteen(Request $request)
    {
        try {

            $request->validate([
                'nama' => ['required', 'string', 'min:3', 'max:30'],
                'ukuran' => ['required', 'string', 'min:3', 'max:120'],
                'harga' => ['required', 'numeric'],
                'deskripsi' => ['required', 'string', 'min:3', 'max:350'],
            ]);

            $canteen = new Canteen();

            $canteen->name = $request->nama;
            $canteen->price = $request->harga;
            $canteen->size = $request->ukuran;
            $canteen->description = $request->deskripsi;
            $canteen->image = '';
            $canteen->slug =  uniqid(\Str::slug($request->nama));

            $canteen->save();


            return redirect()->back()->with('success', 'Kantin berhasil di-tambahkan!');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function editCanteenPage($canteen_id)
    {
        $canteen = Canteen::find($canteen_id);

        $data['page_title'] = "Edit Data Kantin";

        $data['user'] = auth()->user();
        $data['canteen'] = $canteen;

        return view('dashboard.canteen.ds-master-edit-canteen', $data);
    }

    public function editCanteen(Request $request)
    {
        try {

            $request->validate([
                'nama' => ['required', 'string', 'min:3', 'max:30'],
                'ukuran' => ['required', 'string', 'min:3', 'max:120'],
                'harga' => ['required', 'numeric'],
                'deskripsi' => ['required', 'string', 'min:3', 'max:350'],
            ]);

            $canteen = Canteen::find($request->id);

            $canteen->name = $request->nama;
            $canteen->price = $request->harga;
            $canteen->size = $request->ukuran;
            $canteen->description = $request->deskripsi;
            $canteen->image = '';

            $canteen->save();

            return redirect()->back()->with('success', 'Kantin berhasil di-update!');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function canteenTablePage(Request $request)
    {
        try {

            $data['page_title'] = "Tabel Kantin";

            $data['canteens'] = Canteen::orderBy('created_at', 'DESC')->get();
            $data['user'] = auth()->user();

            return view('dashboard.canteen.ds-master-canteen-table', $data);
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function deleteCanteen($id)
    {
        try {

            $canteen = Canteen::find($id);

            $canteen->delete();

            return redirect()->back()->with('success', 'Kantin berhasil di hapus!!');
        } catch (\Throwable $th) {

            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
