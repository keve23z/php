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
        Schema::create('Phong', function (Blueprint $table) {
            $table->string('IDPhong', 10)->primary();
            $table->string('IDLoaiPhong', 10);
            $table->string('SoPhong', 20);
            $table->text('MoTa')->nullable();
            $table->boolean('UuTienChinh')->default(0);
            $table->integer('XepHangSao')->nullable();
            $table->string('TrangThai', 50)->nullable(); // Trống, Đã đặt, Đang sử dụng...
            $table->string('UrlAnhPhong', 255)->nullable();
            $table->timestamps();

            $table->foreign('IDLoaiPhong')->references('IDLoaiPhong')->on('LoaiPhong')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Phong');
    }
};
