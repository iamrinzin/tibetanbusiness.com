<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRentViewPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('rent')->create('rent_view_photos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('rent_basic_info_id');
            $table->uuid('user_id');
            $table->string('path');
            $table->string('card',100);
            $table->string('thumb',100);
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
        Schema::connection('rent')->dropIfExists('rent_view_photos');
    }
}
