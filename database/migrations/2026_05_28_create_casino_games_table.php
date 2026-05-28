<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCasinoGamesTable extends Migration
{
    public function up()
    {
        Schema::create('casino_games', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('category'); // slots, table_games, live_casino, card_games, video_poker, specialty
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('provider'); // Software provider (Netent, Microgaming, etc)
            $table->decimal('rtp', 5, 2)->default(95); // Return to Player percentage
            $table->decimal('min_bet', 16, 8);
            $table->decimal('max_bet', 16, 8);
            $table->boolean('status')->default(1);
            $table->boolean('featured')->default(0);
            $table->integer('order')->default(0);
            $table->timestamps();
            $table->index('category');
        });
    }

    public function down()
    {
        Schema::dropIfExists('casino_games');
    }
}
