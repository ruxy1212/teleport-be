<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('organisation_user', function (Blueprint $table) {
            // $table->id();
            $table->uuid('user_id');
            $table->uuid('organisation_id');
            // $table->timestamps();

            $table->foreign('user_id')->references('userId')->on('users')->onDelete('cascade');
            $table->foreign('organisation_id')->references('orgId')->on('organisations')->onDelete('cascade');
            // $table->primary(['user_id', 'organisation_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisation_user');
    }
};
