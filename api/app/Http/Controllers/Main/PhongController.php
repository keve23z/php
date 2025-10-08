<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\Phong;
use Illuminate\Http\Request;

class PhongController extends Controller
{
    // GET /api/phongs
    public function index()
    {
        return response()->json(Phong::all());
    }

    // GET /api/phongs/{id}
    public function show($id)
    {
        $phong = Phong::with('tienNghis')->find($id);
        if (!$phong) return response()->json(null, 404);
        return response()->json($phong);
    }

    // GET /api/phongs/loai/{maLoai}
    public function searchByLoai($maLoai)
    {
        
        $phongs = Phong::with('tienNghis')->where('IDLoaiPhong', $maLoai)->get();
        return response()->json($phongs);
    }

    // (giữ các method store/update/destroy nếu có)
}
