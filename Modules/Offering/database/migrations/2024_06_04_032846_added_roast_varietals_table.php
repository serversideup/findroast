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
        Schema::create('roast_varietals', function( Blueprint $table ) {
            $table->bigIncrements('id');
            $table->bigInteger('roast_id')->unsigned();
            $table->foreign('roast_id')->references('id')->on('roasts');
            $table->bigInteger('varietal_id')->unsigned();
            $table->foreign('varietal_id')->references('id')->on('varietals');
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
