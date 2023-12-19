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
            $table->timestamp('letter_date')->nullable();
            $table->timestamp('received_date')->nullable();
            $table->enum('type', ['incoming', 'outgoing']);
            $table->string('letter_number')->nullable();
            $table->longText('note')->nullable();
            $table->longText('content')->nullable();
            $table->longText('regarding')->nullable();
            $table->string('from')->nullable();
            $table->string('to')->nullable();
            $table->enum('status', ['pending', 'published', 'disposition', 'require_revision', 'rejected'])->default('pending');
            $table->string('classification_code');
            $table->foreign('classification_code')->references('code')->on('classifications');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
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
