<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->date('moved_at');
            $table->foreignId('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->string('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['animal_id', 'moved_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movements');
    }
};
