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
        Schema::create('signup_otps', function (Blueprint $table) {
           $table->uuid('id')->nullable(); 
            $table->string('otp');
            $table->timestamp('expire_at')->nullable(); 
            $table->string('type');
            $table->string('phone');        
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
        Schema::dropIfExists('signup_otps');
    }
};
