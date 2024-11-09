<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReReviewRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('re_review_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beneficiary_form_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('note');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('re_review_requests');
    }
}
