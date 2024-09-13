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
        Schema::table('roasts', function (Blueprint $table) {
            $table->renameColumn('image', 'primary_image');
            $table->renameColumn('image_disk', 'primary_image_disk');
            $table->string('details_card')->nullable()->after('primary_image_disk');
            $table->string('details_card_disk')->nullable()->after('details_card');
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
