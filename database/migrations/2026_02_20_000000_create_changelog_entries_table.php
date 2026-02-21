<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('changelog_entries', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->date('date');
            $table->text('description');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index('published_at');
            $table->index('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('changelog_entries');
    }
};
