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
        Schema::create('sms', function (Blueprint $table) {
            $table->uuid('id')->nullable(); 
            $table->string('name');
            $table->string('phone');
            $table->string('password');
            $table->string('white_label_name')->nullable(); 
            $table->string('hashed_password');
            $table->integer('free _sms')->default(0);
            $table->integer('num_of _sms')->default(0);
            $table->float('balance',10,2)->default(0.00); 
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
        Schema::dropIfExists('sms');
    }
};
