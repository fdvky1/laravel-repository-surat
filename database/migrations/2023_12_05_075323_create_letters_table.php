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
            $table->unsignedBigInteger('created_by');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('letter_date');
            $table->timestamp('received_date');
            $table->string('summary')->default('');
            $table->string('letter_number');
            $table->string('information');
            $table->string('from');
            $table->string('to');
            $table->string('classification_code');
            $table->foreign('classification_code')->references('code')->on('classifications');
            $table->enum('type', ['incoming', 'outgoing']);
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
