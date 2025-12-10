<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('patient_records', static function ($table) {
            $table->id();
            $table->foreignId('patient_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('visit_id')->constrained();
            $table->text('current_complains')->nullable();
            $table->text('on_direct_questions')->nullable();
            $table->text('issues')->nullable();
            $table->text('updates')->nullable();
            $table->text('on_examinations')->nullable();
            $table->json('vitals')->nullable();
            $table->text('investigations')->nullable();
            $table->text('impression')->nullable();
            $table->text('plan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_records');
    }
};
