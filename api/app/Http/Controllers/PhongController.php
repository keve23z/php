<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Phong;

class PhongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
    * @return \Illuminate\Http\JsonResponse
     */
     // GET: /api/phongs
    public function index()
    {
    // Return the records as stored in the database (do not include related tien_nghis)
    $phongs = Phong::all();

    return response()->json($phongs);
    }

    

    /**
     * Show the form for creating a new resource.
     *
    * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\JsonResponse
     */
    // POST: /api/phongs
    public function store(Request $request)
    {
        $validated = $request->validate([
            'IDLoaiPhong' => 'required|integer',
            'SoPhong' => 'required|string|unique:Phong,SoPhong',
            'MoTa' => 'nullable|string',
            'UuTienChinh' => 'nullable|boolean',
            'XepHangSao' => 'nullable|integer',
            'TrangThai' => 'nullable|string|max:50',
            'UrlAnhPhong' => 'nullable|string|max:255',
        ]);

        $phong = Phong::create($validated);

        return response()->json($phong, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Phong  $phong
    * @return \Illuminate\Http\JsonResponse
     */
    // GET: /api/phongs/{id}
    public function show($id)
    {
    // Return the record exactly as stored in the database (no related tien_nghis)
    $phong = Phong::findOrFail($id);
    return response()->json($phong);
    }

    // ...existing code...

    /**
     * Convenience: search phongs by IDLoaiPhong using path param.
     * Example: GET /api/phongs/loai/LP001
     */
    public function searchByLoai($maLoai)
    {
        // include related tienNghis so each room contains its amenities
        $phongs = Phong::with('tienNghis')->where('IDLoaiPhong', $maLoai)->get();
        return response()->json($phongs);
    }

    /**
     * Return a single (first) room for a given IDLoaiPhong including its amenities.
     * Example: GET /api/phongs/loai/LP001/first
     */
    public function searchFirstByLoai($maLoai)
    {
        $phong = Phong::with('tienNghis')
            ->where('IDLoaiPhong', $maLoai)
            ->orderBy('SoPhong', 'asc')
            ->first();

        if (!$phong) {
            return response()->json(['message' => 'Không tìm thấy phòng cho mã loại ' . $maLoai], 404);
        }

        return response()->json($phong);
    }

    /**
     * Return detailed info for a room including its amenities.
     * Example: GET /api/phongs/{id}/details
     */
    public function details($id)
    {
        $phong = Phong::with('tienNghis')->findOrFail($id);
        return response()->json($phong);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Phong  $phong
    * @return void
     */
    public function edit(Phong $phong)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Phong  $phong
    * @return \Illuminate\Http\JsonResponse
     */
    // PUT: /api/phongs/{id}
    public function update(Request $request, $id)
    {
        $phong = Phong::findOrFail($id);
        $phong->update($request->all());
        return response()->json($phong);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Phong  $phong
    * @return \Illuminate\Http\JsonResponse
     */
    // DELETE: /api/phongs/{id}
    public function destroy($id)
    {
        $phong = Phong::findOrFail($id);
        $phong->delete();
        return response()->json(['message' => 'Đã xoá phòng']);
    }
}
