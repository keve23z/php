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
        Schema::create('TienNghiPhong', function (Blueprint $table) {
           $table->string('IDTienNghiPhong', 10)->primary();
            $table->string('IDPhong', 10);
            $table->string('IDTienNghi', 10);
            $table->timestamps();

            $table->foreign('IDPhong')->references('IDPhong')->on('Phong')->onDelete('cascade');
            $table->foreign('IDTienNghi')->references('IDTienNghi')->on('TienNghi')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TienNghiPhong');
    }
};
