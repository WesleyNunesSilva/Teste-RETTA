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
        Schema::create('votings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique(); // API ID
            $table->foreignId('legislative_proposal_id')->constrained('legislative_proposals')->onDelete('cascade');
            $table->string('title');
            $table->dateTime('date');
            $table->string('result');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votacoes');
    }
};
