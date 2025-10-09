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
     * Trả về tất cả bản ghi LoaiPhong dưới dạng JSON.
     * Sử dụng LoaiPhong::all() — nếu dữ liệu lớn cần paginate thay vì all().
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Lấy toàn bộ loại phòng từ DB
        $loaiPhongs = LoaiPhong::all();

        // Trả về JSON cho client (status 200)
        return response()->json($loaiPhongs);
    }

    /**
     * Show the form for creating a new resource.
     *
     * Không sử dụng trong API (giữ để tương thích resource controller).
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // API typically doesn't serve HTML forms — method left intentionally empty.
    }

    /**
     * Store a newly created resource in storage.
     *
     * - Validate input (ID optional, TenLoaiPhong bắt buộc).
     * - Nếu client không cung cấp IDLoaiPhong, sinh tự động (generateNewId()).
     * - Tạo bản ghi và trả về JSON với status 201 (created).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate incoming data — quy định các kiểu dữ liệu cho từng trường
        $validated = $request->validate([
            'IDLoaiPhong' => 'nullable|string|max:50|unique:LoaiPhong,IDLoaiPhong',
            'TenLoaiPhong' => 'required|string|max:100',
            'MoTa' => 'nullable|string',
            'SoNguoiToiDa' => 'nullable|integer',
            'GiaCoBanMotDem' => 'nullable|numeric',
            'UrlAnhLoaiPhong' => 'nullable|string|max:255',
            'UuTienChinh' => 'nullable|boolean',
        ]);

        // Nếu không có ID do client cung cấp thì tự sinh ID mới dạng LP001, LP002...
        if (empty($validated['IDLoaiPhong'])) {
            $validated['IDLoaiPhong'] = $this->generateNewId();
        }

        // Tạo bản ghi trong DB (mass assignment dựa trên $fillable của model)
        $loaiPhong = LoaiPhong::create($validated);

        // Trả về bản ghi mới tạo với HTTP 201
        return response()->json($loaiPhong, 201);
    }

    /**
     * Display the specified resource.
     *
     * Route model binding injects LoaiPhong $loaiPhong nếu route khai báo.
     * Trả về bản ghi dưới dạng JSON.
     *
     * @param  \App\Models\LoaiPhong  $loaiPhong
     * @return \Illuminate\Http\Response
     */
    public function show(LoaiPhong $loaiPhong)
    {
        return response()->json($loaiPhong);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * Không sử dụng trong API JSON.
     *
     * @param  \App\Models\LoaiPhong  $loaiPhong
     * @return \Illuminate\Http\Response
     */
    public function edit(LoaiPhong $loaiPhong)
    {
        // Left empty for API usage.
    }

    /**
     * Update the specified resource in storage.
     *
     * IMPORTANT: hiện chưa implement — nếu cần, validate và update tương tự store().
     *
     * Ví dụ:
     *   $validated = $request->validate([...]);
     *   $loaiPhong->update($validated);
     *
     */
    public function update()
    {
        // TODO: implement update logic (validate + save)
    }

    /**
     * Remove the specified resource from storage.
     *
     * IMPORTANT: hiện chưa implement — nếu cần, tìm bản ghi và xóa.
     */
    public function destroy()
    {
        // TODO: implement delete logic
    }

    /**
     * Generate a new IDLoaiPhong like LP001, LP002 ...
     *
     * - Lấy bản ghi có ID lớn nhất theo thứ tự DESC.
     * - Tách phần số và tăng lên 1.
     * - Trả về chuỗi có tiền tố 'LP' với padding 3 chữ số.
     *
     * @return string
     */
    private function generateNewId()
    {
        // Lấy bản ghi có ID theo pattern 'LP%' và sắp xếp giảm dần
        $last = LoaiPhong::where('IDLoaiPhong', 'like', 'LP%')
            ->orderBy('IDLoaiPhong', 'desc')
            ->first();

        // Nếu tìm được và ID có phần số ở cuối, tăng số đó; nếu không, bắt đầu từ 1
        if ($last && preg_match('/LP0*(\d+)$/', $last->IDLoaiPhong, $m)) {
            $num = intval($m[1]) + 1;
        } else {
            $num = 1;
        }

        // Trả về ID mới, ví dụ LP001
        return 'LP' . str_pad($num, 3, '0', STR_PAD_LEFT);
    }
}
