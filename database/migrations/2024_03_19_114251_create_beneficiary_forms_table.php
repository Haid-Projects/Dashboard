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
        Schema::create('beneficiary_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_id')->constrained('beneficiaries');
            $table->foreignId('specialist_id')->nullable()->constrained('specialists');
            $table->foreignId('state_manager_id')->nullable()->constrained('state_managers');
            $table->text('state_manager_notes')->nullable();
            $table->text('specialist_notes')->nullable();
            $table->boolean('is_opened');
            $table->integer('illness_id');
            $table->boolean('hidden')->default(true);
            $table->float('total_points')->default(0);
            $table->float('rank')->default(0);
            $table->string('form_id')->nullable();
            $table->softDeletes('deleted_at');
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
        Schema::dropIfExists('beneficiary_forms');
    }
};
