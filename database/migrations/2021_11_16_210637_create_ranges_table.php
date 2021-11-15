<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ranges', static function (Blueprint $table) {
            $table->id();
            $table->timestampRange('range')->unique();
            $table->timestamps();

            $table->spatialIndex('range');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ranges');
    }
};
