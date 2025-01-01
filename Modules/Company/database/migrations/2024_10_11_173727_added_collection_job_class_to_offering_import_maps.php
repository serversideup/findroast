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
        Schema::table('offering_import_maps', function (Blueprint $table) {
            $table->string('collection_job_class')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offering_import_maps', function (Blueprint $table) {
            $table->dropColumn('collection_job_class');
        });
    }
};
