<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('LoaiPhong', function (Blueprint $table) {
            $table->string('IDLoaiPhong', 10)->primary();
            $table->string('TenLoaiPhong', 100);
            $table->text('MoTa')->nullable();
            $table->integer('SoNguoiToiDa')->nullable();
            $table->decimal('GiaCoBanMotDem', 18, 2)->nullable();
            $table->string('UrlAnhLoaiPhong', 255)->nullable();
            $table->boolean('UuTienChinh')->default(0);
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
        Schema::dropIfExists('LoaiPhong');
    }
};
