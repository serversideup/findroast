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
            $table->string('shopify_tags_include')->nullable();
            $table->string('shopify_tags_exclude')->nullable();
            $table->string('shopify_collection_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('offering_import_maps', function (Blueprint $table) {
            $table->dropColumn('shopify_tags_include');
            $table->dropColumn('shopify_tags_exclude');
            $table->dropColumn('shopify_collection_url');
        });
    }
};
