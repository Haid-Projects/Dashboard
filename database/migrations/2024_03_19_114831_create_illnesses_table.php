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
        Schema::create('illnesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services'); // search what to do if it is null
            $table->string('name');
            $table->string('icon')->default("/icons/icon1.png");
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
        Schema::dropIfExists('illnesses');
    }
};
