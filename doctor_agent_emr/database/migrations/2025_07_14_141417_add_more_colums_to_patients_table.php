<?php

declare(strict_types=1);

use App\Enums\MaritalStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('patients', static function (Blueprint $table) {
            $table->string('religion')->nullable();
            $table->enum('marital_status', [MaritalStatus::Married->value, MaritalStatus::Single->value, MaritalStatus::Widowed->value, MaritalStatus::Divorced->value])->nullable();
            $table->string('occupation')->nullable();
            $table->json('medical_history')->nullable();
            $table->json('drug_history')->nullable();
            $table->json('obstetric_history')->nullable();
            $table->json('social_history')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', static function (Blueprint $table) {
            $table->dropColumn([
                'religion',
                'marital_status',
                'occupation',
                'medical_history',
                'drug_history',
                'obstetric_history',
                'social_history'
            ]);
        });
    }
};
