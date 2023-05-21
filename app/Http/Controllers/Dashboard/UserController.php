<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use function GuzzleHttp\json_encode;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //

    public function usersTable(Request $req)
    {

        $data['page_title'] = "Tabel User";

        $data['user'] = auth()->user();
        $data['users'] = User::all();

        return view('dashboard.ds-users-tables', $data);
    }

    public function profilePage(Request $req)
    {

        $data['page_title'] = "Profile Page";

        $data['user'] = auth()->user();
        $data['page'] = 'profile_page';

        return view('dashboard.profile_pages.ds-profile-page', $data);
    }

    public function changePasswordPage(Request $req)
    {

        $data['page_title'] = "Change Password Page";

        $data['user'] = auth()->user();
        $data['page'] = 'change_password_page';

        return view('dashboard.profile_pages.ds-profile-page', $data);
    }

    public function profileConfigPage(Request $req)
    {

        $data['page_title'] = "Profile Config Page";

        $data['user'] = auth()->user();
        $data['page'] = 'profile_config_page';

        return view('dashboard.profile_pages.ds-profile-page', $data);
    }

    public function addUserPage(Request $request)
    {

        $data['page_title'] = "Add User ";

        $data['user'] = auth()->user();
        $data['faker'] = fake('id');

        return view('dashboard.ds-add-user-page', $data);
    }

    public function addUser(Request $req)
    {
        $req->validate([
            'email' => ['required', 'email', 'unique:users,email'],
            'nama_lenkap' => ['required', 'string', 'min:4', 'max:24'],
            'nama_pengguna' => ['required', 'alpha_num', 'min:4', 'max:24'],
            'nomor_telp' => ['required', 'numeric', 'min:4'],
            'password' => ['required', 'min:4', 'max:50'],
            'password_confirm' => ['same:password'],
            'alamat_ktp' => ['required', 'string', 'min:4'],
            'alamat_tinggal' => ['string', 'min:4'],
            'kota' => ['string', 'required', 'min:3'],
            'zip_code' => ['alpha_num'],
        ]);

        try {

            $user = new User();

            $user->email = $req->email;
            $user->name = $req->nama_lenkap;
            $user->username = $req->nama_pengguna;
            $user->image = "";
            $user->nomor_telp = $req->nomor_telp;
            $user->kota = $req->kota;
            $user->alamat_ktp = $req->alamat_ktp;
            $user->alamat_sekarang = $req->alamat_tinggal;
            $user->zip = $req->zip_code;
            $user->sosmed = json_encode([
                'facebook' => $req->fb,
                'instagram' => $req->ig,
            ]);
            $user->password = Hash::make($req->password);

            $user->save();

            return redirect()->route('addUserPage')->with('success', "User berhasil di buat");
        } catch (\Throwable $th) {
            return redirect()->route('addUserPage')->with('error', $th->getMessage());
        }
    }

    public function updateUserData(Request $req)
    {
        try {

            $user_id = Crypt::decryptString($req->key);

            $user = User::find($user_id);

            $req->validate([
                'email' => ['required', 'email', 'unique:users,email,' . $user->id],
                'nama_lenkap' => ['required', 'string', 'min:4', 'max:24'],
                'nama_pengguna' => ['required', 'alpha_num', 'min:4', 'max:24', 'unique:users,username,' . $user->id],
                'nomor_telp' => ['required', 'numeric', 'min:4'],
                'alamat_ktp' => ['required', 'string', 'min:4'],
                'alamat_sekarang' => ['string', 'min:4'],
                'kota' => ['string', 'required', 'min:3'],
                'zip_code' => ['alpha_num'],
            ]);


            $user->email = $req->email;
            $user->name = $req->nama_lenkap;
            $user->username = $req->nama_pengguna;
            $user->image = "";
            $user->nomor_telp = $req->nomor_telp;
            $user->kota = $req->kota;
            $user->alamat_ktp = $req->alamat_ktp;
            $user->alamat_sekarang = $req->alamat_sekarang;
            $user->zip = $req->zip_code;

            $user->save();

            return redirect()->route('profilePage')->with('success', "Data berhasil di-update");
        } catch (ValidationException $v) {
            throw $v;
        } catch (\Throwable $th) {
            return redirect()->route('profilePage')->with('error', $th->getMessage());
        }
    }

    public function changePassword(Request $req)
    {
        try {

            $user = auth()->user();

            if (Hash::check($req->password, $user->password) == false) {
                return redirect()->route('changePasswordPage')
                    ->with('error', 'Password yang anda masukan tidak sesuai dengan password sebelumnya');
            }

            $req->validate([
                'new_password' => ['required', 'min:4', 'max:120'],
                'confirm_password' => ['same:new_password'],
            ]);

            return redirect()->route('changePasswordPage')->with('success', "Password berhasil di-ubah");

        } catch (\Throwable $th) {
            return redirect()->route('changePasswordPage')->with('error', $th->getMessage());
        }
    }

    private function _addNewUser(array $data): \App\Models\User
    {
        return new User();
    }
}
