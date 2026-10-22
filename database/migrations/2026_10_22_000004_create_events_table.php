<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('code', 64)->unique();
            $table->string('name', 255);
            $table->text('description');
            $table->dateTime('start_at');
            // Only students of this faculty (CAMT, ENG or MED) can register.
            $table->string('faculty', 16);

            // A location or category that still has events cannot be deleted.
            $table->foreignId('location_id')->constrained()->restrictOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            // Keep the event if the SMO who created it is deleted.
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
