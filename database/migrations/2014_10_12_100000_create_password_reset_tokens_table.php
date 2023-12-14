<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHeaderInfoTable extends Migration
{
    public function up()
    {
        Schema::create('header_info', function (Blueprint $table) {
            $table->id();
            $table->string('header');
            $table->string('subheader');
            $table->text('address');
            $table->string('signature'); // Sepuh (tanda tangan)
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('header_info');
    }
}
