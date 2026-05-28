<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCryptoWithdrawalsTable extends Migration
{
    public function up()
    {
        Schema::create('crypto_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('crypto_id')->constrained('cryptocurrencies')->onDelete('cascade');
            $table->decimal('amount', 16, 8);
            $table->string('wallet_address');
            $table->string('transaction_hash')->nullable()->unique();
            $table->decimal('network_fee', 16, 8)->default(0);
            $table->tinyInteger('status')->default(0); // 0=pending, 1=processing, 2=completed, 3=failed, 4=cancelled
            $table->text('notes')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('crypto_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('crypto_withdrawals');
    }
}
