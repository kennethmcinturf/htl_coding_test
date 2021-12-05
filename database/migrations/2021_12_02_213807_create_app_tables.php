<?php

use App\Key;
use App\Technician;
use App\Vehicle;
use App\VehicleKey;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 19, 2)->default(0);
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('year');
            $table->string('make');
            $table->string('model');
            $table->string('vin');
            $table->timestamps();
        });

        Schema::create('technicians', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->unsignedInteger('truck_number');
            $table->timestamps();
        });

        Schema::create('vehicle_keys', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('key_id')->index();
            $table->unsignedBigInteger('vehicle_id')->index();

            $table->foreign('key_id')->references('id')->on('keys');
            $table->foreign('vehicle_id')->references('id')->on('vehicles');

            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('key_id')->index();
            $table->unsignedBigInteger('technician_id')->index();

            $table->foreign('key_id')->references('id')->on('keys');
            $table->foreign('technician_id')->references('id')->on('technicians');

            $table->timestamps();
        });

        for ($x = 1; $x <= 10; $x++) {
            $key = new Key();
            $key->name = 'Key # '.$x;
            $key->description = 'This is key number '.$x;
            $key->price = number_format($x, 2);
            $key->save();

            $technician = new Technician();
            $technician->first_name = 'Tech';
            $technician->last_name = '#'.$x;
            $technician->truck_number = $x;
            $technician->save();

            if ($x % 2 !== 0) {
                continue;
            }

            $vehicle = new Vehicle();
            $vehicle->year = '200'.$x;
            $vehicle->make = 'Ford';
            $vehicle->model = 'F-'.$x.'50';
            $vehicle->vin = '8273737'.$x;
            $vehicle->save();

            for ($i = 1; $i <= 2; $i++) {
                $vehicleKey = new VehicleKey();
                $vehicleKey->key_id = $i == 1 ? bcsub($x, 1) : $x;
                $vehicleKey->vehicle_id = $vehicle->id;
                $vehicleKey->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vehicle_keys');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('keys');
        Schema::dropIfExists('technicians');
    }
}
