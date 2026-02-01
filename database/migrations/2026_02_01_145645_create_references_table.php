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
        Schema::create('references', function (Blueprint $table) {
            $table->id();
            $table->integer('parent_id')->nullable();
            $table->string('title');
            $table->string('url')->nullable();
            $table->string('description');
            $table->string('media_url')->nullable();
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('references')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('references');
    }
};
