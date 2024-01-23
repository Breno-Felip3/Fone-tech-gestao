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
        Schema::create('pedido_servico_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_servico_id')->constrained('pedido_servicos');
            $table->foreignId('servico_id')->constrained('servicos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedido_servico_items');
    }
};
