<?php


namespace App\Helpers;

use App\Services\Midtrans\CreateSnapTokenService;

trait RentHelper
{

    public function validasiRentDate($start_date, $end_date)
    {

        $mulai = new \DateTime($start_date);
        $akhir = new \DateTime($end_date);
        $sekarang = new \DateTime();

        $data = null;

        // Validasi tanggal mulai tidak boleh melebihi tanggal sekarang
        if ($mulai < $sekarang) {
            $data = [
                'field' => 'start_date',
                'msg' => 'Tanggal Mulai Sudah Terlewat Harap Pilih Tanggal Minimal H+1',
            ];
        }

        // Validasi tanggal mulai tidak boleh melebihi tanggal sekarang
        if ($akhir < $sekarang) {
            $data = [
                'field' => 'end_date',
                'msg' => 'Tanggal Ahkir Sudah Terlewat Harap Pilih Tanggal Minimal H+2',
            ];
        }

        if (empty($start_date)) {
            $data = [
                'field' => 'start_date',
                'msg' => 'Harap masukan tanggal mulai sewa!!',
            ];
        }

        if (empty($end_date)) {
            $data = [
                'field' => 'end_date',
                'msg' => 'Harap masukan tanggal ahkir sewa!!',
            ];
        }


        if ($mulai == $akhir) {
            $data = [
                'field' => 'end_date',
                'msg' => 'Tanggal Ahkir Minimal H+1 dari tanggal mulai',
            ];
        }

        // Validasi tanggal akhir tidak boleh melebihi tanggal mulai
        if ($akhir < $mulai) {
            $data = [
                'field' => 'end_date',
                'msg' => 'Tanggal akhir tidak boleh melebihi tanggal mulai',
            ];
        }

        if ($this instanceof \Livewire\Component && $data !== null)
            $this->addError($data['field'], $data['msg']);

        if ($data === null) return true;

        return $data;
    }



    private function hitungDurasi($start_date = null, $end_date = null)
    {
        $mulai = new \DateTime($start_date ?? $this->start_date);
        $akhir = new \DateTime($end_date ?? $this->end_date);

        $durasi = $mulai->diff($akhir);

        $hasil = $durasi->days . " hari";

        return [
            'format' => trim($hasil),
            'real' => $durasi->days,
        ];
    }

    private function createSnapToken($trx_data, $item_data, $user_data)
    {
        $midtrans = new CreateSnapTokenService($trx_data, $item_data, $user_data);

        return $midtrans->getSnapToken();
    }


    private function hitungPajak($rentalAmount)
    {
        // Mengatur tarif pajak (misalnya, 10%)
        $taxRate = 0.08;

        // Melakukan perhitungan pajak
        $taxAmount = $rentalAmount * $taxRate;

        // Mengembalikan jumlah pajak yang harus dibayar
        return $taxAmount;
    }
}
