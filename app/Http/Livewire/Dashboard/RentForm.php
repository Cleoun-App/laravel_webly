<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;
use App\Models\Masters\Building;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use App\Models\Administrations\RentBuilding;
use App\Models\Transactions\Rental;
use Illuminate\Support\Facades\DB;
use App\Services\Midtrans\CreateSnapTokenService;
use App\Models\User;
use App\Models\Transactions\Order;
use App\Models\Logger\RentalLog;
use App\Helpers\RentHelper;

class RentForm extends Component
{
    use RentHelper;

    public $order_id;

    public $building_price;

    public $can_next = false;

    public $adm_fee, $tax_fee;

    public $renter_id, $note, $building_id, $start_date, $end_date, $promo_code;

    public $duration, $_duration, $total_payment, $status_payment, $payment_exp, $payment_detail = [];

    public $renter, $building;

    public $form_page = 'penyewaan';

    public function mount()
    {
        $this->adm_fee = config('app.adm_fee');

        $this->fakeData();
    }

    private function fakeData()
    {
        $this->promo_code = "XNXX100";

        $this->renter_id = 2;
        $buildings = Building::getAvailableBuildings();

        if (count($buildings) == 0) return;

        $building = $buildings[rand(0, count($buildings) - 1)];

        $this->building_id = $building->id;
        $this->building_price = "RP " .  number_format($building->price, 0, ',', '.');

        $now = new Carbon();
        $now_ = new Carbon();
        $rand_end = rand(2, 16);

        $start_date = $now->addDay(1);
        $end_date = $now_->addDays($rand_end);

        $this->start_date = $start_date->format('Y-m-d');
        $this->end_date = $end_date->format('Y-m-d');

        $duration = $this->hitungDurasi($start_date->format('Y-m-d'), $end_date->format('Y-m-d'));

        $this->note = fake()->sentence;

        $cost = $building->price * $duration['real'];
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
        $building = Building::find($this->building_id);

        $user = \App\Models\User::find($this->renter_id);

        $this->validate();

        $this->validasiBuilidng($building);
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

    public function validasiBuilidng(Building $building)
    {
        if ($building instanceof Building) {
            $isAvail = $building->isFree();

            if (!$isAvail) $this->addError('building_id', 'Gedung tidak dapat di-pilih(tersewa)');

            $this->building = $building;
        } else {
            $this->addError('building_id', 'Gedung tidak di temukan');
        }
    }

    public function validasiRenter(User $user)
    {
        if ($user instanceof User === false) $this->addError('renter_id', 'Pengguna tidak di temukan!!');
    }

    public function calculate()
    {
        try {
            $this->validate();

            $building = Building::find($this->building_id);

            $_duration = $this->hitungDurasi();

            $rent_cost = $building->price * $_duration['real'];
            $tax_fee = $this->hitungPajak($rent_cost);

            $this->tax_fee = $tax_fee;

            $this->duration = $_duration['format'];
            $this->_duration = $_duration['real'];
            $this->total_payment = $rent_cost + $tax_fee + $this->adm_fee;

            $this->renter = \App\Models\User::find($this->renter_id);
            $this->building = $building;

            $this->can_next = true;
        } catch (ValidationException $ve) {
            $this->can_next = false;
            throw $ve;
        } catch (\Throwable $th) {
            $this->can_next = false;
        }
    }

    public function booking()
    {
        try {
            DB::beginTransaction();

            $building = Building::find($this->building_id);
            $customer = \App\Models\User::find($this->renter_id);

            $rent_building = new RentBuilding();
            $trx_rental = new Rental();
            $order = new Order();

            $trx_rental->start_date = $this->start_date;
            $trx_rental->end_date = $this->end_date;
            $trx_rental->duration = $this->_duration;
            $trx_rental->cost = $this->total_payment;
            $trx_rental->note = $this->note;
            $trx_rental->customer_id = $this->renter_id;

            $trx_rental->save();

            $order->key = uniqid(time());
            $order->type = 'rent_building';
            $order->total_price = intval($this->total_payment);
            $order->transaction()->associate($trx_rental);
            $order->user()->associate($customer);

            $order_id = $order->key;

            $this->order_id = $order_id;

            $trx_data['order_id'] = $order_id;
            $trx_data['gross_amount'] = $building->price * $this->_duration;
            $trx_data['tax_fee'] = $this->tax_fee;
            $trx_data['adm_fee'] = $this->adm_fee;
            $trx_data['data'] = [
                'trx_type' => 'rental',
                'rent_start' => $this->start_date,
                'rent_end' => $this->end_date,
                'duration' => $this->duration,
            ];

            $item_data['name'] = $building->name;
            $item_data['price'] = $building->price;
            $item_data['duration'] = $this->_duration;
            $item_data['data'] = [];

            $user_data['name'] = $customer->name;
            $user_data['email'] = $customer->email;
            $user_data['phone'] = $customer->nomor_telp;

            $snap_token = $this->createSnapToken($trx_data, $item_data, $user_data);

            // update snap token
            $order->snap_token = $snap_token;

            $rent_building->rent()->associate($trx_rental);
            $rent_building->user()->associate($customer);
            $rent_building->building()->associate($building);

            $rent_building->save();

            $this->order_id = $order->key;

            $order->transaction_data = [
                'rent_data' => [
                    'rent_id' => $rent_building->id,
                    'rent_model' => get_class($rent_building),
                    'trx_name' => "Penyewaan Gedung " . $building->name,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'duration' => $this->duration,
                    'item_id' => $building->id,
                    'item_model' => get_class($building),
                    'item_name' => $building->name,
                    'item_price' => $building->price,
                ],
                'customer_data' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'phone' => $customer->nomor_telp,
                ]
            ];

            $order->save();

            RentalLog::logTrx($trx_rental);

            DB::commit();

            session()->flash('success', 'Gedung berhasil di-Booking!, silahkan lanjutkan pembayaran');

            $this->redirectRoute('adm.building.show.trx', [$order->key]);
        } catch (\Throwable $th) {
            DB::rollBack();

            // session()->flash('error', 'Pembayaran Gagal, Silahkan Coba beberapa saat lagi!!');

            session()->flash('error', $th->getMessage());
        }
    }

    private function hasErrors()
    {
        return count($this->getErrorBag()->all()) > 0;
    }
}
