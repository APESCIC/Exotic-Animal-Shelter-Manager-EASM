<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('lost_found_reports', function (Blueprint $table) {
            $table->id();
            $table->string('type', 16)->index();
            $table->string('species');
            $table->string('colour')->nullable();
            $table->string('markings')->nullable();
            $table->string('identifying_code')->nullable()->index();
            $table->string('location_area')->nullable();
            $table->date('reported_at')->index();
            $table->foreignId('person_id')->nullable()->constrained('people')->nullOnDelete();
            $table->foreignId('matched_animal_id')->nullable()->constrained('animals')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_found_reports');
    }
};
