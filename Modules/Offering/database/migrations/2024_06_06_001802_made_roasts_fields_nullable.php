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
        Schema::table('roasts', function( Blueprint $table ){
            $table->string('name')->nullable()->change();
            $table->string('price')->nullable()->change();
            $table->string('image')->nullable()->change();
            $table->dropColumn('first_seen');
            $table->dropColumn('last_seen');
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
