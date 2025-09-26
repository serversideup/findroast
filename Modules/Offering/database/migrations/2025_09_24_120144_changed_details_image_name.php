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
            $table->renameColumn('details_card', 'meta_data_image');
            $table->renameColumn('details_card_disk', 'meta_data_image_disk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roasts', function (Blueprint $table) {
            $table->renameColumn('meta_data_image', 'details_card');
            $table->renameColumn('meta_data_image_disk', 'details_card_disk');
        });
    }
};
