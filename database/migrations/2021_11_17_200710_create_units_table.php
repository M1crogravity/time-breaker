<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('range_id');
            $table->string('intervals')->index();
            $table->json('result');
            $table->timestamps();

            $table->unique(['range_id', 'intervals']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('units');
    }
};
