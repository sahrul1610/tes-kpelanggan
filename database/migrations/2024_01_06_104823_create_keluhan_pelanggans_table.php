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
        Schema::create('keluhan_pelanggan', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50)->nullable(false)->comment('min length 3');
            $table->string('email', 100)->nullable(false);
            $table->string('nomor_hp', 15)->nullable()->comment('numeric only');
            $table->char('status_keluhan', 1)->default('0')->comment('0:Received, 1:In Process, 2: Done');
            $table->string('keluhan', 255)->nullable(false)->comment('min length 50');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('keluhan_pelanggan');
    }

};
