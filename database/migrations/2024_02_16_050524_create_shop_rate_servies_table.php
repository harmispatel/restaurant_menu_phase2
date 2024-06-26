<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopRateServiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shop_rate_servies', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id');
            $table->string('name')->nullable();
            $table->string('en_name')->nullable();
            $table->string('fr_name')->nullable();
            $table->string('el_name')->nullable();
            $table->string('it_name')->nullable();
            $table->string('es_name')->nullable();
            $table->string('de_name')->nullable();
            $table->string('bg_name')->nullable();
            $table->string('tr_name')->nullable();
            $table->string('ro_name')->nullable();
            $table->string('sr_name')->nullable();
            $table->string('zh_name')->nullable();
            $table->string('ru_name')->nullable();
            $table->string('pl_name')->nullable();
            $table->string('ka_name')->nullable();
            $table->tinyInteger('status')->default(0);
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
        Schema::dropIfExists('shop_rate_servies');
    }
}
