<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('category')->default('home')->after('slug');
            $table->unsignedInteger('compare_at_price_cents')->nullable()->after('price_cents');
            $table->string('badge')->nullable()->after('category');
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained();
            $table->unsignedInteger('quantity')->default(1);
            $table->unsignedInteger('unit_price_cents');
            $table->string('product_name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['category', 'compare_at_price_cents', 'badge']);
        });
    }
};
