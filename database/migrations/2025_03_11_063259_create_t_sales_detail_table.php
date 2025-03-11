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
        Schema::create('t_sales_detail', function (Blueprint $table) {
            $table->id();
            $table->uuid('t_sales_id');
            $table->uuid('m_product_id');
            $table->uuid('m_product_detail_id');
            $table->integer('total_item');
            $table->decimal('price', 10, 2);
            $table->timestamps();

            $table->foreign('t_sales_id')->references('id')->on('t_sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_sales_detail');
    }
};
