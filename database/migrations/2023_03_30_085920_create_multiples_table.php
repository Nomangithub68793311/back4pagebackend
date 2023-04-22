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
        Schema::create('multiples', function (Blueprint $table) {
             $table->uuid('id')->primary();           
            $table->string('country');
            $table->string('state');
            $table->json('city');
            $table->string('service');
            $table->string('category');
            $table->string('title');
            $table->text('description');
            $table->string('email');
            $table->string('phone');
            $table->integer('views')->nullable();
            $table->string('tag');
            $table->integer('age');
	    $table->json('images');
	    $table->boolean('preview')->default(true);
            $table->boolean('highlight')->default(false); 
            $table->boolean('blink')->default(false); 
            $table->uuid('account_id')->nullable(); 
            $table->foreign('account_id')
            ->references('id')
            ->on('accounts')
            ->onDelete('cascade');
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
        Schema::dropIfExists('multiples');
    }
};
