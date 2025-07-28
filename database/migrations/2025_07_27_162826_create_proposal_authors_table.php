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
        Schema::create('proposal_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('legislative_proposal_id')->constrained()->onDelete('cascade');
            $table->foreignId('deputy_id')->nullable()->constrained()->nullOnDelete(); // Se for deputado
            $table->string('name'); // Nome do autor (deputado ou órgão)
            $table->string('type'); // "Deputado(a)", "Órgão do Poder Legislativo", etc
            $table->string('uri')->nullable(); // URL da API
            $table->boolean('is_proponent')->default(false);
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_authors');
    }
};
