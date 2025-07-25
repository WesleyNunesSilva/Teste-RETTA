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
        Schema::create('legislative_proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique(); // API ID
            $table->foreignId('deputy_id')->constrained('deputies');
            $table->string('type_acronym', 10); // sigla_tipo
            $table->integer('number');
            $table->integer('year');
            $table->text('summary'); // ementa
            $table->string('status'); // situacao
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposicoes');
    }
};
