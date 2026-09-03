<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('animal_id')->constrained()->cascadeOnDelete();
            $table->string('type', 32)->index();
            $table->string('name');
            $table->date('due_on')->nullable()->index();
            $table->date('given_on')->nullable()->index();
            $table->date('expires_on')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['animal_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
