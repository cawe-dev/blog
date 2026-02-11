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
        Schema::create('content_post_reference', function (Blueprint $table) {
            $table->id();
            $table->text('context')->nullable();
            $table->string('term');
            $table->timestamps();


            $table->foreignId('content_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('reference_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_post_reference');
    }
};
