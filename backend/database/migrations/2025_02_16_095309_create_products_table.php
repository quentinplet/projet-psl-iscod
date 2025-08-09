<?php

use App\Models\User;
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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 191)->unique();
            $table->string('slug', 191)->unique();
            $table->string('reference', 191)->unique();
            $table->string('image_path', 2000)->nullable();
            $table->string('image_mime', 2000)->nullable();
            $table->integer('image_size')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('quantity')->default(0);

            $table->foreignId('category_id')->constrained('categories')->onDelete('restrict');
            $table->foreignId('supplier_id')->constrained('suppliers')->onDelete('restrict');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
