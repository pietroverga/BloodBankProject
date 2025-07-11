<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blood_samples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('facility_id')
                ->constrained();
            $table->foreignId('nurse_id')
                ->constrained('users');

            $table->string('blood_type');
            $table->string('rh_factor');
            $table->unsignedInteger('volume_ml');
            $table->date('collection_date');
            $table->date('expiration_date');
            $table->enum('status', ['available', 'requested', 'used'])->default('available');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_samples');
    }
};