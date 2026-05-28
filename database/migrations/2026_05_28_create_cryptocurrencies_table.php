<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptocurrenciesTable extends Migration
{
    public function up()
    {
        Schema::create('cryptocurrencies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('symbol')->unique();
            $table->string('binance_symbol'); // Símbolo en Binance (ej: BTCUSDT)
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->decimal('current_price', 16, 8)->default(0);
            $table->timestamp('last_updated')->nullable();
            $table->decimal('min_bet', 16, 8)->default(0.00001);
            $table->decimal('max_bet', 16, 8)->default(1000000);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cryptocurrencies');
    }
}
