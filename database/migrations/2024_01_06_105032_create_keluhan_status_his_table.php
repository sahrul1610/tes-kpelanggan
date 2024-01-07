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
        Schema::create('keluhan_status_his', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('keluhan_id')->unsigned()->nullable(false);
            $table->foreign('keluhan_id')->references('id')->on('keluhan_pelanggan');
            $table->char('status_keluhan', 1)->nullable(false)->comment('0:Received, 1:In Process, 2: Done');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('keluhan_status_his');
    }
};
