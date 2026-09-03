<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->text('details')->nullable();
            $table->date('started_on')->nullable();
            $table->date('ended_on')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['animal_id', 'started_on']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diets');
    }
};
