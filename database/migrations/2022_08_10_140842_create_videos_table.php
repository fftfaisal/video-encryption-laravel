<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string( 'title' );
            $table->string( 'description' );
            $table->string( 'original_name' );
            $table->string( 'disk' );
            $table->string( 'path' );
            $table->integer('processing_percentage')->default(0);
            $table->datetime( 'converted_for_streaming_at' )->nullable();
            $table->datetime( 'convert_start_for_streaming_at' )->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('videos');
    }
};
