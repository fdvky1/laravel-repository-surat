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
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->timestamp('received_date')->nullable();
            $table->string('summary')->default('');
            $table->string('letter_number');
            $table->string('note')->default('');
            $table->foreignId('from')->constrained('users')->cascadeOnDelete();
            $table->foreignId('to')->constrained('users')->cascadeOnDelete();
            $table->string('classification_code');
            $table->foreign('classification_code')->references('code')->on('classifications');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
