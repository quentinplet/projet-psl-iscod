<?php

use App\Enums\OrderStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_price', 20, 2);
            $table->enum('status', array_column(OrderStatus::cases(), 'value'))->default(OrderStatus::Pending->value);
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->dateTime(('expected_delivery_date'))->nullable();
            $table->dateTime('delivered_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
