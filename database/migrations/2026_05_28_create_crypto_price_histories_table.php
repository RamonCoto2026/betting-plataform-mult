<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoPriceHistoriesTable extends Migration
{
    public function up()
    {
        Schema::create('crypto_price_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crypto_id')->constrained('cryptocurrencies')->onDelete('cascade');
            $table->decimal('price', 16, 8);
            $table->timestamp('timestamp');
            $table->timestamps();
            $table->index('crypto_id');
            $table->index('timestamp');
        });
    }

    public function down()
    {
        Schema::dropIfExists('crypto_price_histories');
    }
}
