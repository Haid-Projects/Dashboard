<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateModificationLogsTable extends Migration
{
    public function up()
    {
        Schema::create('modification_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beneficiary_form_id');
            $table->unsignedBigInteger('session_id')->nullable();
            $table->text('modifications');
            $table->float('average_points_percentage')->default(0);
            $table->timestamps();

            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->foreign('beneficiary_form_id')->references('id')->on('beneficiary_forms')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('modification_logs');
    }
}
