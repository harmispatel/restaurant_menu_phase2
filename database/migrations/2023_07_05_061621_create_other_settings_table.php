<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id');
            $table->string('key');
            $table->text('en_value')->nullable();
            $table->text('fr_value')->nullable();
            $table->text('el_value')->nullable();
            $table->text('it_value')->nullable();
            $table->text('es_value')->nullable();
            $table->text('de_value')->nullable();
            $table->text('bg_value')->nullable();
            $table->text('tr_value')->nullable();
            $table->text('ro_value')->nullable();
            $table->text('sr_value')->nullable();
            $table->text('zh_value')->nullable();
            $table->text('ru_value')->nullable();
            $table->text('pl_value')->nullable();
            $table->text('ka_value')->nullable();
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
        Schema::dropIfExists('other_settings');
    }
}
