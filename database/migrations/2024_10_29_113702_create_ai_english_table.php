<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAiEnglishTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ai_english', function (Blueprint $table) {
            $table->id();
            $table->string('browser_fingerprint')->default('');;
//            $table->text('input');
            $table->string('input')->default('');;
//            $table->enum('mode', ['search', 'random']);
            $table->string('mode', 30)->default('');;
//            $table->longText('ai_response');
            $table->text('ai_response')->default('');;
//            $table->integer('star')->nullable();
            $table->integer('star')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ai_english');
    }
}
