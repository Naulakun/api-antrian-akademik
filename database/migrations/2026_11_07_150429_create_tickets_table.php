<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->cascadeOnDelete();
            $table->foreignId('counter_id')->nullable()->constrained('counters')->nullOnDelete();
            $table->string('ticket_number', 20);
            $table->string('student_nim', 20);
            $table->enum('status', ['waiting', 'called', 'completed', 'cancelled'])->default('waiting');
            $table->timestamps();

            // Index performa query antrean aktif harian
            $table->index(['created_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};