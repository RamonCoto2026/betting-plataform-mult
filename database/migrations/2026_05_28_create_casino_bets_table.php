<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasinoBetsTable extends Migration
{
    public function up()
    {
        Schema::create('casino_bets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('casino_session_id')->constrained('casino_sessions')->onDelete('cascade');
            $table->foreignId('casino_game_id')->constrained('casino_games')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('bet_amount', 16, 8);
            $table->decimal('win_amount', 16, 8)->default(0);
            $table->json('result_data')->nullable(); // Datos del resultado del juego
            $table->tinyInteger('status')->default(0); // 0=pending, 1=won, 2=lost, 3=draw
            $table->timestamps();
            $table->index('casino_session_id');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('casino_bets');
    }
}
