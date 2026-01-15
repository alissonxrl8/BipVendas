<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('produtos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('codigo_barras')->unique();
        $table->string('nome');
        $table->decimal('preco_venda', 10, 2);
        $table->decimal('preco_custo', 10, 2);
        $table->integer('estoque');
        $table->integer('estoque_minimo')->default(0);
        $table->text('descricao')->nullable();
        $table->string('foto')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};
