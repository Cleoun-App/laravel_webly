<?php

namespace App\Http\Livewire\Dashboard;

use App\Helpers\RentHelper;
use App\Models\Administrations\RentCar;
use App\Models\Logger\RentalLog;
use Illuminate\Support\Facades\DB;
use App\Models\Transactions\Order;
use App\Models\Transactions\Rental;
use App\Models\Masters\Car;
use App\Models\Masters\Driver;
use Illuminate\Database\Eloquent\Model;

class RentCarForm extends RentForm
{
    use RentHelper;

    public function mount() {
        $this->context = "Mobil";
        $this->item_model = Car::class;
        $this->fakeData(Car::getAvailableCars());
    }

    public function render($item = null)
    {

        $view = view('livewire.dashboard.rent-car-form', [
            'cars'  => Car::getAvailableCars(),
            'drivers' => Driver::getAvailableDrivers(),
            'driver_cost' => $this->driver_cost,
        ]);

        return parent::render($view->render());
    }


    public function updatedDriverId($value)
    {
        try {
            $dv = Driver::findOrFail($value);

            $dv_cost = ($dv->price ?? 120000);

            $this->driver_cost = $dv_cost;
        } catch (\Throwable $th) {
            $this->driver_cost = 0;
        }
    }

    public function updatedItemId($value)
    {
        try {
            $car = Car::find($value);

            $this->item_price = "RP " .  number_format($car->price, 0, ',', '.');
        } catch (\Throwable $th) {
            $this->item_price = 0;
        }
    }

    public function booking()
    {
        try {
            DB::beginTransaction();

            $car = Car::findOrFail($this->item_id);
            $customer = \App\Models\User::findOrFail($this->renter_id);
            $driver = Driver::find($this->driver_id);

            $dv_cost = $this->driver_cost * $this->_duration;

            $rent_car = new RentCar();
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
            $order->type = 'rent_car';
            $order->total_price = intval($this->total_payment);
            $order->transaction()->associate($trx_rental);
            $order->user()->associate($customer);

            $order_id = $order->key;

            $this->order_id = $order_id;

            $trx_data['order_id'] = $order_id;
            $trx_data['gross_amount'] = $car->price * $this->_duration;
            $trx_data['tax_fee'] = $this->tax_fee;
            $trx_data['adm_fee'] = $this->adm_fee;
            $trx_data['data'] = [
                'trx_type' => 'rental',
                'rent_start' => $this->start_date,
                'rent_end' => $this->end_date,
                'duration' => $this->duration,
            ];

            $item_data['name'] = $car->name;
            $item_data['price'] = $car->price;
            $item_data['duration'] = $this->_duration;
            $item_data['data'] = [];
            $item_data['driver_data'] = [
                'name' => $driver?->name,
                'cost' => $dv_cost,
            ];

            $user_data['name'] = $customer->name;
            $user_data['email'] = $customer->email;
            $user_data['phone'] = $customer->nomor_telp;

            $snap_token = $this->createSnapToken($trx_data, $item_data, $user_data);

            // update snap token
            $order->snap_token = $snap_token;

            $rent_car->rent()->associate($trx_rental);
            $rent_car->user()->associate($customer);
            $rent_car->car()->associate($car);
            $rent_car->driver()->associate($driver);

            $rent_car->save();

            $this->order_id = $order->key;

            $order->transaction_data = [
                'rent_data' => [
                    'rent_id' => $rent_car->id,
                    'rent_model' => get_class($rent_car),
                    'trx_name' => "Penyewaan Mobil " . $car->name,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'duration' => $this->duration,
                    'item_id' => $car->id,
                    'item_model' => get_class($car),
                    'item_name' => $car->name,
                    'item_price' => $car->price,
                    'driver_name' => $driver?->name,
                    'driver_cost' => $dv_cost,
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

            session()->flash('success', 'Mobil berhasil di-Booking!, silahkan lanjutkan pembayaran');

            $this->redirectRoute('adm.car.show.trx', [$order->key]);
        } catch (\Throwable $th) {
            DB::rollBack();

            // session()->flash('error', 'Pembayaran Gagal, Silahkan Coba beberapa saat lagi!!');

            session()->flash('error', $th->getMessage());
        }
    }
}
