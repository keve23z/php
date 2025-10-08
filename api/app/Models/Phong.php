<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Phong extends Model
{
    use HasFactory;
    protected $table = 'Phong';
    protected $primaryKey = 'IDPhong';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IDLoaiPhong', 'SoPhong', 'TenPhong', 'MoTa', 'UuTienChinh', 'XepHangSao', 'TrangThai', 'UrlAnhPhong'
    ];

    public function tienNghis()
    {
        return $this->belongsToMany(TienNghi::class, 'TienNghiPhong', 'IDPhong', 'IDTienNghi');
    }

    public function loaiPhong()
    {
        return $this->belongsTo(LoaiPhong::class, 'IDLoaiPhong', 'IDLoaiPhong');
    }
}
