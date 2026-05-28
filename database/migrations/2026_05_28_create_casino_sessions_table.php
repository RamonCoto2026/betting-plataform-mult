<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasinoSessionsTable extends Migration
{
    public function up()
    {
        Schema::create('casino_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('casino_game_id')->constrained('casino_games')->onDelete('cascade');
            $table->string('session_token')->unique();
            $table->tinyInteger('status')->default(1); // 1=active, 2=closed
            $table->decimal('total_bets', 16, 8)->default(0);
            $table->decimal('total_winnings', 16, 8)->default(0);
            $table->decimal('net_result', 16, 8)->default(0);
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('casino_game_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('casino_sessions');
    }
}
