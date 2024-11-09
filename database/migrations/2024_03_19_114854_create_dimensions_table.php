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
        Schema::create('dimensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('illness_id')->constrained('illnesses');// search what to do if it is null
            $table->string('name');
            $table->text('tips')->nullable();
            $table->integer('rank');
            $table->integer('age_group')->nullable();
            $table->integer('max_no');
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
        Schema::dropIfExists('dimensions');
    }
};
