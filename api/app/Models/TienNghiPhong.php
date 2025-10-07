<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TienNghiPhong extends Model
{
    use HasFactory;
    protected $table = 'TienNghiPhong';
    protected $primaryKey = 'IDTienNghiPhong';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = ['IDPhong', 'IDTienNghi'];
}
