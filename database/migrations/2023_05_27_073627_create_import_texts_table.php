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
        Schema::create('import_texts', function (Blueprint $table) {
        $table->uuid('id')->primary();
            $table->text('text');
            $table->text('subject');
            $table->json('phone');
            $table->integer('no_of_sms')->nullable();
            $table->string('tag')->default('contact');
            $table->uuid('sms_id')->nullable(); 
            $table->foreign('sms_id')
            ->references('id')
            ->on('sms')
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
        Schema::dropIfExists('import_texts');
    }
};
