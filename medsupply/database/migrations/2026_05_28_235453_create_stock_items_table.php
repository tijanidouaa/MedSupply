<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // consommables, medicaments, equipements, autres
            $table->integer('quantity')->default(0);
            $table->integer('min_quantity')->default(10);
            $table->decimal('price', 10, 2)->default(0);
            $table->foreignId('hospital_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('stock_items');
    }
};