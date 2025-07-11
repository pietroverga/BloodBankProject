<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sample_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')
                ->constrained('users');
            $table->foreignId('facility_id')
                ->constrained();
            $table->foreignId('blood_sample_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null');
            $table->enum('status', ['pending', 'approved', 'denied'])
                ->default('pending');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sample_requests');
    }
};
