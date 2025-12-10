<?php

declare(strict_types=1);

use App\Enums\NhisStatus;
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
            $table->enum('nhis_status', [NhisStatus::ACTIVE->value, NhisStatus::INACTIVE->value, NhisStatus::NOT_APPLICABLE->value])->default(NhisStatus::INACTIVE->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('patients', static function (Blueprint $table) {
            $table->dropColumn([
                'nhis_status',
            ]);
        });
    }
};
