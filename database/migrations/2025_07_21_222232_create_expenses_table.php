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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('external_id')->unique(); // API ID
            $table->foreignId('deputy_id')->constrained('deputies');
            $table->integer('year');
            $table->integer('month');
            $table->string('expense_type');
            $table->decimal('amount', 10, 2);
            $table->string('supplier_name');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('despesas');
    }
};
