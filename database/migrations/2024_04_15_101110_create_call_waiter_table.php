<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCallWaiterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('call_waiter', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id');
            $table->integer('location');
            $table->string('room_or_table_no');
            $table->string('name')->nullable();
            $table->text('message')->nullable();
            $table->tinyInteger('order')->default(0);
            $table->tinyInteger('water')->default(0);
            $table->tinyInteger('pay_bill')->default(0);
            $table->tinyInteger('pay_with_bill')->default(0);
            $table->tinyInteger('other')->default(0);
            $table->tinyInteger('read')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('call_waiter');
    }
}
