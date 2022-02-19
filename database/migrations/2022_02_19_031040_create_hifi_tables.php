<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHifiTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hifi_products', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->index();
            $table->string('gencode')->index();

            $table->string('designation')->index();
            $table->string('photo');

            $table->string('weight');
            $table->string('volume');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hifi_products');
    }
}
