<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TienNghi extends Model
{
    use HasFactory;
    protected $table = 'TienNghi';
    protected $primaryKey = 'IDTienNghi';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'TenTienNghi'
    ];

    public function phongs()
    {
        return $this->belongsToMany(Phong::class, 'TienNghiPhong', 'IDTienNghi', 'IDPhong');
    }
}
