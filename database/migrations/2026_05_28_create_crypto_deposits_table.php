<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoDepositsTable extends Migration
{
    public function up()
    {
        Schema::create('crypto_deposits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('crypto_id')->constrained('cryptocurrencies')->onDelete('cascade');
            $table->decimal('amount', 16, 8);
            $table->string('transaction_hash')->unique();
            $table->string('wallet_address');
            $table->integer('confirmations')->default(0);
            $table->integer('required_confirmations')->default(6);
            $table->tinyInteger('status')->default(0); // 0=pending, 1=confirmed, 3=failed
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('crypto_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('crypto_deposits');
    }
}
