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
        Schema::create('roast_countries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('roast_id')->unsigned();
            $table->foreign('roast_id')->references('id')->on('roasts');
            $table->bigInteger('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries');
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
