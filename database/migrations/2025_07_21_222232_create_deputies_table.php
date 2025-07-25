<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */public function up(): void
{
    Schema::create('deputies', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('external_id')->unique(); // API ID
        $table->foreignId('political_party_id')->constrained('political_parties');
        $table->string('name');
        $table->string('state_acronym', 2); // sigla_uf
        $table->string('email')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deputados');
    }
};
