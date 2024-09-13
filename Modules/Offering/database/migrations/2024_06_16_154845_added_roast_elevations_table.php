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
        Schema::create('roast_elevations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('roast_id')->unsigned();
            $table->foreign('roast_id')->references('id')->on('roasts');
            $table->bigInteger('elevation_id')->unsigned();
            $table->foreign('elevation_id')->references('id')->on('elevations');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
