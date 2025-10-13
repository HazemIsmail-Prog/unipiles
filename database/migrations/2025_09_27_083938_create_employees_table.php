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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('title_id')->nullable()->constrained('titles');
            $table->string('name_ar');
            $table->string('name_en');
            $table->string('degree')->nullable();
            $table->string('cid')->nullable();
            $table->string('actual_salary')->nullable();
            $table->string('ezn_salary')->nullable();
            $table->date('employment_date')->nullable();
            $table->string('residency')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
