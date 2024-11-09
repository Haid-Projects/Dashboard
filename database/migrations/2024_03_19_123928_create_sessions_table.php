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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_form_id')->constrained('beneficiary_forms');
            $table->foreignId('illness_id')->constrained('illnesses');
            $table->foreignId('specialist_id')->constrained('specialists');
            $table->string('name');
            $table->string('location')->default('center of jouzur');
            $table->date('date');
            $table->time('time');
            $table->text('specialist_notes')->nullable();
            $table->text('beneficiary_notes')->nullable();
            $table->float('rate')->nullable();
            $table->boolean('has_attended')->nullable();
            $table->boolean('notification_sent')->default(false);
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
        Schema::dropIfExists('sessions');
    }
};
