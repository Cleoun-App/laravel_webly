<?php

namespace App\Http\Livewire\Dashboard;

use App\Helpers\RentHelper;
use App\Models\Masters\Canteen;
use App\Models\Administrations\RentCanteen;
use App\Models\Logger\RentalLog;
use Illuminate\Support\Facades\DB;
use App\Models\Transactions\Order;
use App\Models\Transactions\Rental;

class RentCanteenForm extends RentForm
{
    use RentHelper;

    public function mount() {
        $this->context = "Kantin";
        $this->item_model = Canteen::class;
        $this->fakeData(Canteen::getAvailableCanteens());
    }

    public function render($item = null)
    {

        $view = view('livewire.dashboard.rent-canteen-form', [
            'canteens'  => Canteen::getAvailableCanteens(),
        ]);

        return parent::render($view->render());
    }

    public function updatedItemId($value)
    {
        try {
            $canteen = Canteen::find($value);

            $this->item_price = "RP " .  number_format($canteen->price, 0, ',', '.');
        } catch (\Throwable $th) {
            $this->item_price = 0;
        }
    }

    public function booking()
    {
        try {
            DB::beginTransaction();

            $canteen = Canteen::find($this->item_id);
            $customer = \App\Models\User::find($this->renter_id);

            $rent_canteen = new RentCanteen();
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
            $order->type = 'rent_canteen';
            $order->total_price = intval($this->total_payment);
            $order->transaction()->associate($trx_rental);
            $order->user()->associate($customer);

            $order_id = $order->key;

            $this->order_id = $order_id;

            $trx_data['order_id'] = $order_id;
            $trx_data['gross_amount'] = $canteen->price * $this->_duration;
            $trx_data['tax_fee'] = $this->tax_fee;
            $trx_data['adm_fee'] = $this->adm_fee;
            $trx_data['data'] = [
                'trx_type' => 'rental',
                'rent_start' => $this->start_date,
                'rent_end' => $this->end_date,
                'duration' => $this->duration,
            ];

            $item_data['name'] = $canteen->name;
            $item_data['price'] = $canteen->price;
            $item_data['duration'] = $this->_duration;
            $item_data['data'] = [];

            $user_data['name'] = $customer->name;
            $user_data['email'] = $customer->email;
            $user_data['phone'] = $customer->nomor_telp;

            $snap_token = $this->createSnapToken($trx_data, $item_data, $user_data);

            // update snap token
            $order->snap_token = $snap_token;

            $rent_canteen->rent()->associate($trx_rental);
            $rent_canteen->user()->associate($customer);
            $rent_canteen->canteen()->associate($canteen);

            $rent_canteen->save();

            $this->order_id = $order->key;

            $order->transaction_data = [
                'rent_data' => [
                    'rent_id' => $rent_canteen->id,
                    'rent_model' => get_class($rent_canteen),
                    'trx_name' => "Penyewaan Gedung " . $canteen->name,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'duration' => $this->duration,
                    'item_id' => $canteen->id,
                    'item_model' => get_class($canteen),
                    'item_name' => $canteen->name,
                    'item_price' => $canteen->price,
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

            session()->flash('success', 'Kantin berhasil di-Booking!, silahkan lanjutkan pembayaran');

            $this->redirectRoute('adm.canteen.show.trx', [$order->key]);
        } catch (\Throwable $th) {
            DB::rollBack();

            // session()->flash('error', 'Pembayaran Gagal, Silahkan Coba beberapa saat lagi!!');

            session()->flash('error', $th->getMessage());
        }
    }
}
