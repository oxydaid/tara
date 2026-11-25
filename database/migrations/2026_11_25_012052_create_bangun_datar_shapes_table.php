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
        Schema::create('bangun_datar_shapes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 60)->index();
            $table->string('subtype', 60)->nullable();
            $table->string('color', 20)->default('#E57354');
            $table->string('custom_image_path')->nullable();
            $table->json('ciri_ciri');
            $table->string('bagian_damar_kurung')->nullable();
            $table->unsignedInteger('urutan')->default(0)->index();
            $table->text('svg_inline')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bangun_datar_shapes');
    }
};
