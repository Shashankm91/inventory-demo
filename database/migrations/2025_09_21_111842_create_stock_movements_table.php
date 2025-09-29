<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockMovementsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['IN', 'OUT', 'ADJUST'])->default('IN');
            $table->integer('quantity');
            $table->string('reference_type')->nullable(); // 'invoice','manual'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->integer('balance_after'); // stock after operation
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable(); // optional
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}
