<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('shop_id');
            $table->string('mail_form_key');
            $table->text('form')->nullable();
            $table->text('en_form')->nullable();
            $table->text('fr_form')->nullable();
            $table->text('el_form')->nullable();
            $table->text('it_form')->nullable();
            $table->text('es_form')->nullable();
            $table->text('de_form')->nullable();
            $table->text('bg_form')->nullable();
            $table->text('tr_form')->nullable();
            $table->text('ro_form')->nullable();
            $table->text('sr_form')->nullable();
            $table->text('zh_form')->nullable();
            $table->text('ru_form')->nullable();
            $table->text('pl_form')->nullable();
            $table->text('ka_form')->nullable();
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
        Schema::dropIfExists('mail_forms');
    }
}
