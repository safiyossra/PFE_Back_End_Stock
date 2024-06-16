<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mvtproduct', function (Blueprint $table) {
            $table->id();
            $table->string('id_Store');
            $table->string('id_delivery');
            $table->string('id_vehicule');
            $table->string('id_TypePanne');
            $table->integer('DateMvt');
            $table->string('TypeMvt'); //entre /sortie
            $table->string('Reference');
            $table->string('NumBon'); //document
             $table->string('Qte');
            $table->string('Tva');
            $table->string('Price');
            $table->string('observation');
            $table->string('Kilometrage');
            $table->string('Extra');
             $table->string('sitaire');
             $table->string('login');
             $table->string('Designation');
            $table->integer('created_at')->length(11);
        });
        Schema::create('Store_Product', function (Blueprint $table) {
            $table->id();
            $table->string('id_Store');
            $table->string('Reference');
             $table->string('Qte');
            $table->string('Price');
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
        Schema::dropIfExists('stocks');
    }
};
