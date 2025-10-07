<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoaiPhong extends Model
{
    use HasFactory;
    protected $table = 'LoaiPhong';
    protected $primaryKey = 'IDLoaiPhong';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'IDLoaiPhong','TenLoaiPhong', 'MoTa', 'SoNguoiToiDa', 'GiaCoBanMotDem', 'UrlAnhLoaiPhong', 'UuTienChinh'
    ];

    public function phongs()
    {
        return $this->hasMany(\App\Models\Phong::class, 'IDLoaiPhong', 'IDLoaiPhong');
    }
}
