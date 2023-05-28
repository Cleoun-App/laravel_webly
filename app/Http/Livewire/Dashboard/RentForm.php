<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Masters\Building;
use Illuminate\Validation\ValidationException;

class RentForm extends Component
{
    public $building_price;

    public $can_next = false;

    public $renter_id, $note, $building_id, $start_date, $end_date;

    public $duration, $total_payment, $status_payment, $payment_exp, $payment_detail = [];

    public $renter;

    public function rules()
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'building_id' => ['required', 'integer'],
            'renter_id' => ['required', 'integer'],
            'note' => ['max:350'],
        ];
    }

    public function render()
    {

        $data['buildings'] = Building::getAvailableBuildings();
        $data['users'] = \App\Models\User::all();

        return view('livewire.dashboard.rent-form', $data);
    }

    public function updatedBuildingId($value)
    {
        try {
            $building = Building::find($value);

            $this->building_price = "RP " .  number_format($building->price, 0, ',', '.');
        } catch (\Throwable $th) {
            $this->building_price = 0;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Validasi tambahan jika property yang diubah adalah 'tanggalMulai' atau 'tanggalAkhir'
        if ($propertyName === 'start_date' || $propertyName === 'end_date') {
            $this->validasiTambahan();
        }

        if ($propertyName === 'building_id') {
            $building = Building::find($this->building_id);

            if ($building instanceof Building) {
                $isAvail = $building->isFree();

                if (!$isAvail) $this->addError('building_id', 'Gedung tidak dapat di-pilih(tersewa)');
            } else {
                $this->addError('building_id', 'Gedung tidak di temukan');
            }
        }


        if ($propertyName === 'renter_id') {
            $user = \App\Models\User::find($this->renter_id);

            if ($user instanceof \App\Models\User === false) $this->addError('renter_id', 'Pengguna tidak di temukan!!');
        }

        if ($this->hasErrors() === false) {
            $this->calculate();
        } else {
            $this->can_next = false;
        }
    }

    public function validasiTambahan()
    {

        $mulai = new \DateTime($this->start_date);
        $akhir = new \DateTime($this->end_date);
        $sekarang = new \DateTime();

        // Validasi tanggal mulai tidak boleh melebihi tanggal sekarang
        if ($mulai < $sekarang) {
            $this->addError('start_date', 'Tanggal Mulai Sudah Terlewat Harap Pilih Tanggal Minimal H+1');
            return;
        }

        // Validasi tanggal mulai tidak boleh melebihi tanggal sekarang
        if ($akhir < $sekarang) {
            $this->addError('end_date', 'Tanggal Ahkir Sudah Terlewat Harap Pilih Tanggal Minimal H+2');
            return;
        }

        if (empty($this->start_date)) {
            $this->addError('star_date', 'Harap masukan tanggal mulai sewa!!');
            return;
        }

        if (empty($this->end_date)) {
            $this->addError('end_date', 'Harap masukan tanggal ahkir sewa!!');
            return;
        }


        if ($mulai == $akhir) {
            $this->addError('end_date', 'Tanggal Ahkir Minimal H+1 dari tanggal mulai');
            return;
        }

        // Validasi tanggal akhir tidak boleh melebihi tanggal mulai
        if ($akhir < $mulai) {
            $this->addError('end_date', 'Tanggal akhir tidak boleh melebihi tanggal mulai');
            return;
        }
    }

    public function calculate()
    {
        try {
            $this->validate();

            $building = Building::find($this->building_id);

            $_duration = $this->hitungDurasi();
            $adm_fee = config('app.adm_fee');
            $tax_fee = config('app.tax_fee');

            $this->duration = $_duration['format'];
            $this->total_payment = $building->price * $_duration['real'] + $adm_fee + $tax_fee;

            $this->renter = \App\Models\User::find($this->renter_id);

            $this->can_next = true;
        } catch (ValidationException $ve) {
            $this->can_next = false;
            throw $ve;
        } catch (\Throwable $th) {
            $this->can_next = false;
        }
    }

    private function hitungDurasi()
    {
        $mulai = new \DateTime($this->start_date);
        $akhir = new \DateTime($this->end_date);

        $durasi = $mulai->diff($akhir);

        $hasil = '';

        if ($durasi->y > 0) {
            $hasil .= $durasi->y . ' tahun ';
        }
        if ($durasi->m > 0) {
            $hasil .= $durasi->m . ' bulan ';
        }
        if ($durasi->d > 0) {
            $hasil .= $durasi->d . ' hari ';
        }
        if ($durasi->h > 0) {
            $hasil .= $durasi->h . ' jam ';
        }
        if ($durasi->i > 0) {
            $hasil .= $durasi->i . ' menit ';
        }
        if ($durasi->s > 0) {
            $hasil .= $durasi->s . ' detik ';
        }

        return [
            'format' => trim($hasil),
            'real' => $durasi->d,
        ];
    }

    private function hasErrors()
    {
        return count($this->getErrorBag()->all()) > 0;
    }
}
