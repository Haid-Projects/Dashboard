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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('full_name');
            $table->date('birthdate');
            $table->text('relative_relation');
            $table->enum('gender', ['male', 'female']);
            $table->string('marital_status');
            $table->string('file_id')->nullable();
            $table->boolean('socially_integrable')->default(false);
            $table->decimal('rate', 3, 1)->default(2.5);
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
        Schema::dropIfExists('beneficiaries');
    }
};
