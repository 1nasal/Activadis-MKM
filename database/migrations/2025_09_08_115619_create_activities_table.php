<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->boolean('includes_food');
            $table->text('description');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('cost', 8, 2);

            $table->integer('max_participants')->nullable();
            $table->integer('min_participants')->nullable();
            $table->string('image')->nullable(); // url

            $table->text('requirements')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
