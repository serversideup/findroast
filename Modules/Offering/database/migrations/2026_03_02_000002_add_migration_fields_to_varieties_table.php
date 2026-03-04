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
        Schema::table('varieties', function (Blueprint $table) {
            $table->foreignId('migrated_to_id')->nullable()->constrained('varieties')->nullOnDelete();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('varieties', function (Blueprint $table) {
            $table->dropForeign(['migrated_to_id']);
            $table->dropColumn('migrated_to_id');
            $table->dropSoftDeletes();
        });
    }
};
