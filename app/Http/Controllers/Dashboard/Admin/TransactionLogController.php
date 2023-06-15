<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Logger\RentalLog;

class TransactionLogController extends Controller
{

    public function logTransactions(Request $request)
    {

        $context = "";
        $log_type  = $request->log_type;

        switch ($log_type) {
            case "rent_building":
                $context = "Gedung";
                break;
            case "rent_car":
                $context = "Mobil";
                break;
            case "rent_canteen":
                $context = "Kantin";
                break;
        }

        $data['page_title'] = "Log Transaksi Penyewaan " . $context;
        $data['user'] = auth()->user();

        $data['logs'] = RentalLog::where(['type' => $log_type])->orderBy('updated_at', 'DESC')->get();

        return view('dashboard.logger.ds-admin-logs-transactions', $data);
    }

    public function logDetail($id)
    {
        $rentalLog = RentalLog::find($id);

        $data['page_title'] = "Log Informasi Transaksi";
        $data['user'] = auth()->user();
        $data['log'] = $rentalLog;

        return view('dashboard.logger.ds-admin-log-detail', $data);
    }

    public function logDel($id)
    {
        try {
            $rentalLog = RentalLog::findOrFail($id);

            $rentalLog->delete();

            return redirect()->route('adm.log.transactions', request('log_type'))->with('success', 'Data log berhasil di-hapus');
        } catch (\Throwable $th) {
            return redirect()->route('adm.log.transactions', request('log_type'))->with('error', $th->getMessage());
        }
    }
}
