<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetTypesTable extends Migration
{
    public function up()
    {
        Schema::create('bet_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sport_id')->nullable()->constrained('sports')->onDelete('cascade');
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('calculation_method')->default('simple'); // simple, complex
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bet_types');
    }
}
