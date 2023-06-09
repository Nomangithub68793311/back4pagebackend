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
        Schema::create('accounts', function (Blueprint $table) {
            

            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email');
            $table->string('image');
            $table->integer('credit')->nullable();
            // $table->boolean('verified')->default(false); 
            // $table->string('code')->nullable();
            // $table->string('hashedPassword');
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
        Schema::dropIfExists('accounts');
    }
};
