<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\User;
use App\Helpers\RentHelper;
use Illuminate\Database\Eloquent\Model;

class RentForm extends Component
{
    use RentHelper;

    public $order_id;

    public $item_price, $item_model;

    public $can_next = false;

    public $adm_fee, $tax_fee;

    public $renter_id, $note, $item_id, $start_date, $end_date, $promo_code;

    public $duration, $_duration, $total_payment, $status_payment, $payment_exp, $payment_detail = [];

    public $renter, $item;

    public $form_page = 'penyewaan';

    public $context;

    public function mount()
    {
        $this->adm_fee = config('app.adm_fee');
    }

    protected function fakeData($items)
    {
        $this->promo_code = "XNXX100";

        $this->renter_id = rand(1, 2);

        if (count($items) == 0) return;

        $item = $items[rand(0, count($items) - 1)];

        $this->item_id = $item->id;
        $this->item_price = "RP " .  number_format($item->price, 0, ',', '.');

        $now = new Carbon();
        $now_ = new Carbon();
        $rand_end = rand(2, 16);

        $start_date = $now->addDay(1);
        $end_date = $now_->addDays($rand_end);

        $this->start_date = $start_date->format('Y-m-d');
        $this->end_date = $end_date->format('Y-m-d');

        $duration = $this->hitungDurasi($start_date->format('Y-m-d'), $end_date->format('Y-m-d'));

        $this->note = fake()->sentence;

        $cost = $item->price * $duration['real'];
        $tax = $this->hitungPajak($cost);

        $this->duration = $duration['format'];
        $this->_duration = $duration['real'];
        $this->total_payment = intval($cost + $tax + $this->adm_fee);
        $this->tax_fee = $tax;

        $this->renter = User::find($this->renter_id);

        $this->can_next = true;
    }

    public function rules()
    {
        return [
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'item_id' => ['required', 'integer'],
            'renter_id' => ['required', 'integer'],
            'note' => ['max:350'],
        ];
    }

    public function render($item = null)
    {
        // $data['buildings'] = Building::getAvailableBuildings();
        $data['users'] = \App\Models\User::all();
        $data['items'] = $item;

        return view('livewire.dashboard.rent-form', $data);
    }


    public function updatedPromoCode($v)
    {
        if ($v != "AX10") {
            $this->addError('promo_code', 'Kode Promo Tidak Tersedia');
        }
    }

    public function prev()
    {
        $this->form_page = 'penyewaan';
    }

    public function next()
    {
        $item = $this->item_model();

        $user = \App\Models\User::find($this->renter_id);

        $this->validate();

        $this->validasiItem($item);
        $this->validasiRenter($user);
        $this->validasiRentDate($this->start_date, $this->end_date);

        $this->calculate();

        if ($this->hasErrors() === false) {
            $this->form_page = 'pembayaran';
        } else {
            $this->can_next = false;
        }
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);

        // Validasi tambahan jika property yang diubah adalah 'tanggalMulai' atau 'tanggalAkhir'
        if ($propertyName === 'start_date' || $propertyName === 'end_date') {
            $this->validasiRentDate($this->start_date, $this->end_date);
        }
    }

    public function validasiItem(Model $item)
    {
        $isAvail = $item->isFree();

        if (!$isAvail) $this->addError('item_id', $this->context . ' tidak dapat di-pilih(tersewa)');

        $this->item = $item;
    }

    public function validasiRenter(User $user)
    {
        if ($user instanceof User === false) $this->addError('renter_id', 'Pengguna tidak di temukan!!');
    }

    public function calculate()
    {
        try {
            $this->validate();

            $item = $this->item_model();

            $_duration = $this->hitungDurasi();

            $rent_cost = $item->price * $_duration['real'];
            $tax_fee = $this->hitungPajak($rent_cost);

            $this->tax_fee = $tax_fee;

            $this->duration = $_duration['format'];
            $this->_duration = $_duration['real'];
            $this->total_payment = $rent_cost + $tax_fee + $this->adm_fee;

            $this->renter = \App\Models\User::find($this->renter_id);
            $this->item = $item;

            $this->can_next = true;
        } catch (ValidationException $ve) {
            $this->can_next = false;
            throw $ve;
        } catch (\Throwable $th) {
            $this->can_next = false;
        }
    }

    private function hasErrors()
    {
        return count($this->getErrorBag()->all()) > 0;
    }

    private function item_model($id = null): Model
    {
        return $this->item_model::find($id ?? $this->item_id);
    }
}
