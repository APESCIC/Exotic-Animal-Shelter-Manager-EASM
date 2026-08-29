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
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('species');
            $table->string('breed_type')->nullable();
            $table->string('sex', 16)->default('unknown');
            $table->date('date_of_birth')->nullable();
            $table->unsignedSmallInteger('age_years')->nullable();
            $table->string('colour')->nullable();
            $table->string('identifying_code')->nullable()->index();
            $table->string('flags')->nullable();
            $table->string('location')->nullable()->index();
            $table->string('bonded_animals')->nullable();
            $table->string('entry_reason')->nullable();
            $table->boolean('non_shelter')->default(false);
            $table->date('deceased_at')->nullable();
            $table->string('death_reason')->nullable();
            $table->string('enclosure')->nullable();
            $table->string('cites')->nullable();
            $table->string('dwa')->nullable();
            $table->string('primary_photo_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
    }
};
