<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\LoaiPhong;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoaiPhongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Return all LoaiPhong rows (raw DB values, including UrlAnhLoaiPhong)
        $loaiPhongs = LoaiPhong::all();
        return response()->json($loaiPhongs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'IDLoaiPhong' => 'nullable|string|max:50|unique:LoaiPhong,IDLoaiPhong',
            'TenLoaiPhong' => 'required|string|max:100',
            'MoTa' => 'nullable|string',
            'SoNguoiToiDa' => 'nullable|integer',
            'GiaCoBanMotDem' => 'nullable|numeric',
            'UrlAnhLoaiPhong' => 'nullable|string|max:255',
            'UuTienChinh' => 'nullable|boolean',
        ]);

        // If client didn't provide an ID, generate one like LP001
        if (empty($validated['IDLoaiPhong'])) {
            $validated['IDLoaiPhong'] = $this->generateNewId();
        }

        $loaiPhong = LoaiPhong::create($validated);
        return response()->json($loaiPhong, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LoaiPhong  $loaiPhong
     * @return \Illuminate\Http\Response
     */
    public function show(LoaiPhong $loaiPhong)
    {
        // Return the LoaiPhong record as stored in DB
        return response()->json($loaiPhong);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\LoaiPhong  $loaiPhong
     * @return \Illuminate\Http\Response
     */
    public function edit(LoaiPhong $loaiPhong)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LoaiPhong  $loaiPhong
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LoaiPhong  $loaiPhong
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
       
    }

    /**
     * Generate a new IDLoaiPhong like LP001, LP002 ...
     *
     * @return string
     */
    private function generateNewId()
    {
        $last = LoaiPhong::where('IDLoaiPhong', 'like', 'LP%')
            ->orderBy('IDLoaiPhong', 'desc')
            ->first();

        if ($last && preg_match('/LP0*(\d+)$/', $last->IDLoaiPhong, $m)) {
            $num = intval($m[1]) + 1;
        } else {
            $num = 1;
        }

        return 'LP' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }
}
