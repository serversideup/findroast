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
            $table->string('collection_url')->nullable();
            $table->string('container_selector')->nullable();
            $table->string('product_list_item_selector')->nullable();
            $table->string('product_selector')->nullable();
            $table->dropColumn('collection_job_class');
            $table->dropColumn('roast_job_class');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offering_import_maps', function (Blueprint $table) {
            $table->dropColumn('collection_url');
            $table->dropColumn('container_selector');
            $table->dropColumn('product_list_item_selector');
            $table->dropColumn('product_selector');
            $table->string('collection_job_class')->nullable();
            $table->string('roast_job_class')->nullable();
        });
    }
};
