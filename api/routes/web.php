<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
// controllers in Main namespace
use App\Http\Controllers\Main\PhongController;
use App\Http\Controllers\Main\TienNghiController;
use App\Http\Controllers\Main\LoaiPhongController;

$apiBaseEnv = trim(env('API_URL', ''), "/"); // set API_URL in .env if remote, e.g. http://127.0.0.1:8001
// final API prefix used by helpers - falls back to local dispatch via /api
$apiPrefix = $apiBaseEnv ? ($apiBaseEnv . '/api') : '/api';

$callApi = function(string $endpoint, int $timeout = 5) use ($apiPrefix) {
    try {
        // if caller passed a full URL, use it
        if (preg_match('#^https?://#i', $endpoint)) {
            $resp = Http::timeout($timeout)->get($endpoint);
            return $resp->ok() ? $resp->json() : null;
        }

        // normalize endpoint to start with /api/...
        $normalized = preg_replace('#^/+#', '/', $endpoint);
        if (strpos($normalized, '/api/') !== 0) {
            $normalized = '/api' . (strpos($normalized, '/') === 0 ? $normalized : '/' . $normalized);
        }

        // if API prefix is remote, call remote API via HTTP client
        if (preg_match('#^https?://#', $apiPrefix)) {
            $url = rtrim($apiPrefix, '/') . $normalized; // $apiPrefix already contains /api when remote
            $resp = Http::timeout($timeout)->get($url);
            return $resp->ok() ? $resp->json() : null;
        }

        // else perform internal dispatch (same Laravel app)
        $subRequest = Request::create($normalized, 'GET');
        $subResponse = app()->handle($subRequest);
        $status = $subResponse->getStatusCode();
        if ($status >= 200 && $status < 300) {
            return json_decode($subResponse->getContent(), true);
        }
        return null;
    } catch (\Throwable $e) {
        logger()->error("callApi error for [$endpoint]: " . $e->getMessage());
        return null;
    }
};


/*
 |-----------------------------------------------------------------------
 | Homepage: load room types from API and attach a first_phong_id for Details link
 |-----------------------------------------------------------------------
 */
Route::get('/', function (Request $request) use ($callApi) {
    $data = $callApi('/api/loaiphongs') ?: [];
    $rooms = is_array($data) ? $data : [];

    // Attach first_phong_id (Pxxx) for each room type so "Details" links can point to an actual room
    foreach ($rooms as $i => $r) {
        $rooms[$i]['first_phong_id'] = null;
        if (!empty($r['IDLoaiPhong'])) {
            $list = $callApi('/api/phongs/loai/' . urlencode($r['IDLoaiPhong']));
            if (is_array($list) && count($list) > 0) {
                // list may be associative or numeric-indexed; retrieve first item
                $first = array_values($list)[0] ?? null;
                if (is_array($first) && !empty($first['IDPhong'])) {
                    $rooms[$i]['first_phong_id'] = $first['IDPhong'];
                }
            }
        }
    }

    return view('welcome', compact('rooms'));
});


/*
 |-----------------------------------------------------------------------
 | Rooms by type view (rooms2)
 | expects /rooms2?type=LP001
 |-----------------------------------------------------------------------
 */
$rooms2Handler = function (Request $request) use ($callApi) {
    $type = $request->query('type', null);
    if (!$type) {
        return redirect('/'); // fallback
    }

    $roomsDetail = $callApi('/api/phongs/loai/' . urlencode($type)) ?: [];
    $typeInfo = $callApi('/api/loaiphongs/' . urlencode($type));
    $typeName = is_array($typeInfo) ? ($typeInfo['TenLoaiPhong'] ?? ($typeInfo['ten'] ?? null)) : null;

    return view('rooms2', compact('roomsDetail', 'type', 'typeName'));
};

// register both URIs -> same handler
Route::get('/rooms2.php', $rooms2Handler);
Route::get('/rooms2', $rooms2Handler);


/*
 |-----------------------------------------------------------------------
 | Room details route
 | Template links use /roomdetails.php?id=...
 |-----------------------------------------------------------------------
 */
Route::get('/roomdetails.php', function (Request $request) use ($callApi) {
    $rawId = $request->query('id', null);
    $room = null;
    $resolvedId = null;

    // Nếu truyền ID phòng (Pxxx) thì lấy chi tiết phòng, nếu truyền ID loại lấy phòng đầu tiên của loại
    if ($rawId) {
        if (is_string($rawId) && substr($rawId,0,1) === 'P') {
            $room = $callApi('/api/phongs/' . urlencode($rawId)) ?: null;
        } else {
            $list = $callApi('/api/phongs/loai/' . urlencode($rawId));
            $room = (is_array($list) && count($list)) ? array_values($list)[0] : null;
        }
        $resolvedId = $room['IDPhong'] ?? $rawId;
    }

    // Lấy danh sách loại phòng để hiển thị "Phòng Tương Tự"
    $rooms = $callApi('/api/loaiphongs') ?: [];

    // Bổ sung thông tin phòng đại diện (first_phong_id, ảnh, giá) nếu API loaiphongs không trả đủ
    foreach ($rooms as $i => $type) {
        $typeId = $type['IDLoaiPhong'] ?? null;
        if (!$typeId) continue;
        $list = $callApi('/api/phongs/loai/' . urlencode($typeId));
        $first = (is_array($list) && count($list)) ? array_values($list)[0] : null;
        if ($first) {
            $rooms[$i]['first_phong_id'] = $first['IDPhong'] ?? null;
            if (empty($rooms[$i]['UrlAnhLoaiPhong'])) {
                $rooms[$i]['UrlAnhLoaiPhong'] = $first['UrlAnhPhong'] ?? null;
            }
            if (empty($rooms[$i]['GiaCoBanMotDem'])) {
                $rooms[$i]['GiaCoBanMotDem'] = $first['Gia'] ?? null;
            }
        }
    }

    // expose $id for the view (compact expects 'id')
    $id = $rawId;

    return view('roomdetails', compact('id','room','resolvedId','rooms'));
});



