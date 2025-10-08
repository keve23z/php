<?php
// Ensure UTF-8 output
if (!headers_sent()) header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

// Normalization helper — requires ext-intl (Normalizer). Fallback if not available.
if (!function_exists('safe_text')) {
    function safe_text($value) {
        if ($value === null) return '';
        if (!is_string($value)) $value = (string)$value;
        // normalize to composed form (NFC) when possible
        if (class_exists('Normalizer')) {
            $norm = \Normalizer::normalize($value, \Normalizer::FORM_C);
            if ($norm !== false) $value = $norm;
        }
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<!-- Mirrored from duruthemes.com/demo/html/cappa/demo6-light/room-details.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 18 Sep 2025 01:56:17 GMT -->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <title>The Cappa Luxury Hotel</title>

    <!-- Google Fonts used by custom.css -->
    <link rel="preconnect" href="https://fonts.googleapis.com/">
    <link rel="preconnect" href="https://fonts.gstatic.com/" crossorigin>
    <!-- Use Playfair Display for headings (fallbacks included) -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;700&family=Barlow:wght@400&display=swap" rel="stylesheet">

    <!-- core styles (use root-relative paths to public/) -->
    <link rel="stylesheet" href="/HomePage/css/plugins.css" />
    <link rel="stylesheet" href="/HomePage/css/style.css" />
    <link rel="stylesheet" href="/HomePage/css/custom.css" />

    <style>
      /* ensure section-title uses fonts from custom.css */
      section.rooms-page .section-title {
        font-family: "Playfair Display", "Gilda Display", "Barlow", serif !important;
        font-weight: 400 !important;
        line-height: 1.15 !important;
        letter-spacing: 0.02em;
      }
      /* nhỏ gọn: đảm bảo overlay/tiện ích hiển thị */
      .room-wrap{padding:60px 30px}
      .room-main{display:flex;gap:30px;align-items:flex-start}
      .room-img{flex:0 0 55%; max-width:55%}
      .room-img img{width:100%;height:420px;object-fit:cover}
      .room-info{flex:1}
      .amenities ul{list-style:none;padding:0;margin:0}
      .amenities li{margin:8px 0;display:flex;gap:8px;align-items:center}
      @media (max-width:991px){ .room-main{flex-direction:column} .room-img{max-width:100%} .room-img img{height:260px} }
    </style>
</head>
<body>
    <?php
    // $room should be passed from the route; keep a safe fallback to null
    if (!isset($room)) {
        $room = null;
    }
    if (!isset($id)) {
        $id = isset($_GET['id']) ? trim($_GET['id']) : null;
    }
    ?>
    <!-- Preloader -->
    <div class="preloader-bg"></div>
    <div id="preloader">
        <div id="preloader-status">
            <div class="preloader-position loader"> <span></span> </div>
        </div>
    </div>
    <!-- Progress scroll totop -->
    <div class="progress-wrap cursor-pointer">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" />
        </svg>
    </div>
    <!-- Simplified Menu (rooms-only) -->
    <div class="cappa-wrap">
        <div class="cappa-wrap-inner">
            <nav class="cappa-menu">
                <ul>
                    <li><a href="rooms.html">Rooms</a></li>
                    <li><a href="rooms2.html">Rooms 2</a></li>
                    <li><a href="rooms3.html">Rooms 3</a></li>
                    <li><a href="room-details.html" class="active">Room Details</a></li>
                    <li><a href="#" class="reservation-link">Reservation: <strong>855 100 4444</strong></a></li>
                </ul>
            </nav>
            <div class="cappa-menu-footer">
                <div class="reservation">
                    <a href="tel:8551004444">
                        <div class="icon d-flex justify-content-center align-items-center">
                            <i class="flaticon-call"></i>
                        </div>
                        <div class="call">Reservation<br><span>855 100 4444</span></div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- Logo & Menu Burger -->
    <header class="cappa-header">
        <div class="container">
            <div class="row align-items-center">
                <!-- Logo -->
                <div class="col-6 col-md-6 cappa-logo-wrap">
                    <a href="index.html" class="cappa-logo"><img src="HomePage/img/logo.png" alt=""></a>
                </div>
                <!-- Menu Burger -->
                <div class="col-6 col-md-6 text-right cappa-wrap-burger-wrap"> <a href="#" class="cappa-nav-toggle cappa-js-cappa-nav-toggle"><i></i></a> </div>
            </div>
        </div>
    </header>
    <!-- Room Page Slider -->
    <header class="header slider">
        <div class="owl-carousel owl-theme">
            <!-- The opacity on the image is made with "data-overlay-dark="number". You can change it using the numbers 0-9. -->
            <div class="text-center item bg-img" data-overlay-dark="3" data-background="HomePage/img/slider/3.jpg"></div>
            <div class="text-center item bg-img" data-overlay-dark="3" data-background="HomePage/img/slider/2.jpg"></div>
            <div class="text-center item bg-img" data-overlay-dark="3" data-background="HomePage/img/slider/5.jpg"></div>
        </div>
        <!-- arrow down -->
        <div class="arrow bounce text-center">
            <a href="#" data-scroll-nav="1" class=""> <i class="ti-arrow-down"></i> </a>
        </div>
    </header>
    <!-- Room Content -->
    <section class="rooms-page section-padding" data-scroll-index="1">
        <div class="container">
            <!-- project content -->
            <div class="row">
                <div class="col-md-12"> 
                    <span>
                        <?php
                            $rating = is_array($room) && isset($room['XepHangSao']) ? (int)$room['XepHangSao'] : 5;
                            for ($s = 1; $s <= 5; $s++) {
                                $class = $s <= $rating ? 'star-rating' : 'star-rating star-empty';
                                echo '<i class="' . $class . '"></i>';
                            }
                        ?>
                    </span>
                    <div class="section-subtitle"><?php echo safe_text($room['SoPhong'] ?? ''); ?></div>
                    <div class="section-title"><?php echo safe_text($room['TenPhong'] ?? 'Room Details'); ?></div>
                </div>
                <div class="col-md-8">
                    <p class="mb-30"><?php echo safe_text($room['MoTa'] ?? 'Hotel non lorem ac erat suscipit bibendum nulla facilisi.'); ?></p>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Nhận phòng (Check-in)</h6>
                            <ul class="list-unstyled page-list mb-30">
                                <li>
                                    <div class="page-list-icon"> <span class="ti-check"></span> </div>
                                    <div class="page-list-text">
                                        <p>Thời gian nhận phòng: Từ 09:00 sáng trở đi.</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="page-list-icon"> <span class="ti-check"></span> </div>
                                    <div class="page-list-text">
                                        <p>Việc nhận phòng sớm có thể được sắp xếp tùy theo tình trạng phòng trống tại thời điểm đến.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6>Nhận phòng (Check-out)</h6>
                            <ul class="list-unstyled page-list mb-30">
                                <li>
                                    <div class="page-list-icon"> <span class="ti-check"></span> </div>
                                    <div class="page-list-text">
                                        <p>Thời gian trả phòng: Trước 12:00 trưa</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="page-list-icon"> <span class="ti-check"></span> </div>
                                    <div class="page-list-text">
                                        <p>Khách sạn cung cấp dịch vụ trả phòng nhanh để quý khách tiết kiệm thời gian.</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-12">
                            <h6>Hướng dẫn nhận phòng đặc biệt</h6>
                            <p>Khách sẽ nhận được email 5 ngày trước khi đến với hướng dẫn nhận phòng; nhân viên lễ tân sẽ chào đón khách khi đến. Để biết thêm chi tiết, vui lòng liên hệ với cơ sở sử dụng thông tin trên xác nhận đặt phòng.</p>
                        </div>
                        <div class="col-md-12" style="line-height:1.35;">
                            <h6>Trẻ em và giường phụ</h6>
                            <p style="margin-bottom:6px;">Trẻ em ở miễn phí khi sử dụng giường đã có sẵn.</p>
                            <p style="margin-bottom:6px;">Lưu ý: Trẻ em có thể không được áp dụng chương trình bữa sáng miễn phí.</p>
                            <p style="margin-bottom:6px;">Giường phụ hoặc giường di động được cung cấp theo yêu cầu với giá 100.000 VNĐ mỗi ngày.</p>
                        </div>
                        <div class="col-md-12">
                            <div class="butn-dark mt-15 mb-30"> <a href="rooms2.html"><span>Check Now</span></a> </div>
                        </div>
                    </div>
                </div>
                    <div class="col-md-3 offset-md-1">
                    <h6>Tiện ích</h6>
                    <ul class="list-unstyled page-list mb-30">
<?php
    // chuẩn hoá nguồn tiện nghi từ $room (hỗ trợ nhiều tên trường)
    $amenities = [];
    if (is_array($room)) {
        if (!empty($room['tien_nghis']) && is_array($room['tien_nghis'])) {
            $amenities = $room['tien_nghis'];
        } elseif (!empty($room['tienNghis']) && is_array($room['tienNghis'])) {
            $amenities = $room['tienNghis'];
        } elseif (!empty($room['tien_nghi']) && is_array($room['tien_nghi'])) {
            $amenities = $room['tien_nghi'];
        }
    }

    if (!empty($amenities)) :
        foreach ($amenities as $amen) :
            $label = '';
            if (is_array($amen)) {
                $label = $amen['TenTienNghi'] ?? $amen['ten'] ?? $amen['name'] ?? '';
            } else {
                $label = (string)$amen;
            }
?>
        <li>
            <div class="page-list-icon"> <span class="ti-check"></span> </div>
            <div class="page-list-text">
                <p><?php echo safe_text($label); ?></p>
            </div>
        </li>
    <?php
        endforeach;
    else:
        // fallback nếu không có dữ liệu từ API
    ?>
        <li>
            <div class="page-list-icon"> <span class="flaticon-group"></span> </div>
            <div class="page-list-text"><p>1-2 Persons</p></div>
        </li>
        <li>
            <div class="page-list-icon"> <span class="flaticon-wifi"></span> </div>
            <div class="page-list-text"><p>Free Wifi</p></div>
        </li>
    <?php endif; ?>
</ul>
                </div>
            </div>
        </div>
    </section>
    <!-- Similiar Room -->
    <section class="rooms1 section-padding bg-blck">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-subtitle"><span>Luxury Hotel</span></div>
                    <div class="section-title"><span>Phòng Tương Tự</span></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="owl-carousel owl-theme">
                        <?php if (!empty($rooms) && is_array($rooms)): ?>
                            <?php foreach ($rooms as $index => $room): ?>
                                <?php
                                    $img = isset($room['UrlAnhLoaiPhong']) ? $room['UrlAnhLoaiPhong'] : '1.jpg';
                                    // prefer rooms/, then slider/, fallback to rooms/1.jpg
                                    $pathRooms = public_path('HomePage/img/rooms/' . $img);
                                    $pathSlider = public_path('HomePage/img/slider/' . $img);
                                    if (file_exists($pathRooms)) {
                                        $imgUrl = '/HomePage/img/rooms/' . rawurlencode($img);
                                    } elseif (file_exists($pathSlider)) {
                                        $imgUrl = '/HomePage/img/slider/' . rawurlencode($img);
                                    } else {
                                        $imgUrl = '/HomePage/img/rooms/1.jpg';
                                    }

                                    $idLoai = $room['IDLoaiPhong'] ?? '';
                                    $priceRaw = $room['GiaCoBanMotDem'] ?? '';
                                    $displayPrice = $priceRaw !== '' ? number_format((float)$priceRaw, 0, '.', ',') . '₫ ' : '';
                                    $name = $room['TenLoaiPhong'] ?? '';
                                    // details link: prefer first_phong_id if provided by API, otherwise use type id
                                    // If first_phong_id is missing or null, fall back to the type id
                                    $detailsId = $room['first_phong_id'] ?? $idLoai;
                                    $bookUrl = '/rooms2?type=' . rawurlencode($idLoai);
                                    $detailsUrl = '/roomdetails.php?id=' . rawurlencode($detailsId);
                                ?>
                                <div class="item">
                                    <div class="position-re o-hidden">
                                        <img src="<?php echo $imgUrl; ?>" alt="<?php echo htmlspecialchars($name); ?>">
                                    </div>
                                    <span class="category"><a href="<?php echo $bookUrl; ?>">Book</a></span>
                                    <div class="con">
                                        <h6><a href="<?php echo $detailsUrl; ?>"><?php echo $displayPrice ?: '—'; ?></a></h6>
                                        <h5><a href="<?php echo $detailsUrl; ?>"><?php echo htmlspecialchars($name); ?></a></h5>
                                        <div class="line"></div>
                                        <div class="row facilities">
                                            <div class="col col-md-7">
                                                <ul>
                                                    <li><i class="flaticon-bed"></i></li>
                                                    <li><i class="flaticon-bath"></i></li>
                                                    <li><i class="flaticon-breakfast"></i></li>
                                                    <li><i class="flaticon-towel"></i></li>
                                                </ul>
                                            </div>
                                            <div class="col col-md-5 text-end">
                                                <div class="permalink"><a href="<?php echo $detailsUrl; ?>">Details <i class="ti-arrow-right"></i></a></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- fallback: static items if API empty -->
                            <div class="item">
                                <div class="position-re o-hidden"> <img src="/HomePage/img/rooms/1.jpg" alt=""> </div>
                                <span class="category"><a href="/rooms2">Book</a></span>
                                <div class="con">
                                    <h6><a href="/roomdetails.php">150$ / Night</a></h6>
                                    <h5><a href="/roomdetails.php">Junior Suite</a></h5>
                                    <div class="line"></div>
                                    <div class="row facilities">
                                        <div class="col col-md-7">
                                            <ul>
                                                <li><i class="flaticon-bed"></i></li>
                                                <li><i class="flaticon-bath"></i></li>
                                                <li><i class="flaticon-breakfast"></i></li>
                                                <li><i class="flaticon-towel"></i></li>
                                            </ul>
                                        </div>
                                        <div class="col col-md-5 text-end">
                                            <div class="permalink"><a href="/roomdetails.php">Details <i class="ti-arrow-right"></i></a></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Pricing -->
    <section class="pricing section-padding">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="section-subtitle"><span>Best Prices</span></div>
                    <div class="section-title">Extra Services</div>
                    <p>The best prices for your relaxing vacation. The utanislen quam nestibulum ac quame odion elementum sceisue the aucan.</p>
                    <p>Orci varius natoque penatibus et magnis disney parturient monte nascete ridiculus mus nellen etesque habitant morbine.</p>
                    <div class="reservations mb-30">
                        <div class="icon"><span class="flaticon-call"></span></div>
                        <div class="text">
                            <p>For information</p> <a href="tel:855-100-4444">855 100 4444</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="owl-carousel owl-theme">
                        <div class="pricing-card">
                            <img src="HomePage/img/pricing/1.jpg" alt="">
                            <div class="desc">
                                <div class="name">Room cleaning</div>
                                <div class="amount">$50<span>/ month</span></div>
                                <ul class="list-unstyled list">
                                    <li><i class="ti-check"></i> Hotel ut nisan the duru</li>
                                    <li><i class="ti-check"></i> Orci miss natoque vasa ince</li>
                                    <li><i class="ti-close unavailable"></i>Clean sorem ipsum morbin</li>
                                </ul>
                            </div>
                        </div>
                        <div class="pricing-card">
                            <img src="HomePage/img/pricing/2.jpg" alt="">
                            <div class="desc">
                                <div class="name">Drinks included</div>
                                <div class="amount">$30<span>/ daily</span></div>
                                <ul class="list-unstyled list">
                                    <li><i class="ti-check"></i> Hotel ut nisan the duru</li>
                                    <li><i class="ti-check"></i> Orci miss natoque vasa ince</li>
                                    <li><i class="ti-close unavailable"></i>Clean sorem ipsum morbin</li>
                                </ul>
                            </div>
                        </div>
                        <div class="pricing-card">
                            <img src="HomePage/img/pricing/3.jpg" alt="">
                            <div class="desc">
                                <div class="name">Room Breakfast</div>
                                <div class="amount">$30<span>/ daily</span></div>
                                <ul class="list-unstyled list">
                                    <li><i class="ti-check"></i> Hotel ut nisan the duru</li>
                                    <li><i class="ti-check"></i> Orci miss natoque vasa ince</li>
                                    <li><i class="ti-close unavailable"></i>Clean sorem ipsum morbin</li>
                                </ul>
                            </div>
                        </div>
                        <div class="pricing-card">
                            <img src="HomePage/img/pricing/4.jpg" alt="">
                            <div class="desc">
                                <div class="name">Safe & Secure</div>
                                <div class="amount">$15<span>/ daily</span></div>
                                <ul class="list-unstyled list">
                                    <li><i class="ti-check"></i> Hotel ut nisan the duru</li>
                                    <li><i class="ti-check"></i> Orci miss natoque vasa ince</li>
                                    <li><i class="ti-close unavailable"></i>Clean sorem ipsum morbin</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Reservation & Booking Form -->
    <section class="testimonials">
        <div class="background bg-img bg-fixed section-padding pb-0" data-background="HomePage/img/slider/2.jpg" data-overlay-dark="2">
            <div class="container">
                <div class="row">
                    <!-- Reservation -->
                    <div class="col-md-5 mb-30 mt-30">
                        <p><i class="star-rating"></i><i class="star-rating"></i><i class="star-rating"></i><i class="star-rating"></i><i class="star-rating"></i></p>
                        <h5>Each of our guest rooms feature a private bath, wi-fi, cable television and include full breakfast.</h5>
                        <div class="reservations mb-30">
                            <div class="icon color-1"><span class="flaticon-call"></span></div>
                            <div class="text">
                                <p class="color-1">Reservation</p> <a class="color-1" href="tel:855-100-4444">855 100 4444</a>
                            </div>
                        </div>
                        <p><i class="ti-check"></i><small>Call us, it's toll-free.</small></p>
                    </div>
                    <!-- Booking From -->
                    <div class="col-md-5 offset-md-2">
                        <div class="booking-box">
                            <div class="head-box">
                                <h6>Rooms & Suites</h6>
                                <h4>Hotel Booking Form</h4>
                            </div>
                            <div class="booking-inner clearfix">
                                <form action="https://duruthemes.com/demo/html/cappa/demo6-light/rooms2.html" class="form1 clearfix">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input1_wrapper">
                                                <label>Check in</label>
                                                <div class="input1_inner">
                                                    <input type="text" class="form-control input datepicker" placeholder="Check in">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="input1_wrapper">
                                                <label>Check out</label>
                                                <div class="input1_inner">
                                                    <input type="text" class="form-control input datepicker" placeholder="Check out">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="select1_wrapper">
                                                <label>Adults</label>
                                                <div class="select1_inner">
                                                    <select class="select2 select" style="width: 100%">
                                                        <option value="0">Adults</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="select1_wrapper">
                                                <label>Children</label>
                                                <div class="select1_inner">
                                                    <select class="select2 select" style="width: 100%">
                                                        <option value="0">Children</option>
                                                        <option value="1">1</option>
                                                        <option value="2">2</option>
                                                        <option value="3">3</option>
                                                        <option value="4">4</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <button type="submit" class="btn-form1-submit mt-15">Check Availability</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Clients -->
    <section class="clients">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                <div class="owl-carousel owl-theme">
                    <div class="clients-logo">
                        <a href="#0"><img src="HomePage/img/clients/1.png" alt=""></a>
                    </div>
                    <div class="clients-logo">
                        <a href="#0"><img src="HomePage/img/clients/2.png" alt=""></a>
                    </div>
                    <div class="clients-logo">
                        <a href="#0"><img src="HomePage/img/clients/3.png" alt=""></a>
                    </div>
                    <div class="clients-logo">
                        <a href="#0"><img src="HomePage/img/clients/4.png" alt=""></a>
                    </div>
                    <div class="clients-logo">
                        <a href="#0"><img src="HomePage/img/clients/5.png" alt=""></a>
                    </div>
                    <div class="clients-logo">
                        <a href="#0"><img src="HomePage/img/clients/6.png" alt=""></a>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer -->
    <footer class="footer">
            <div class="footer-top">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="footer-column footer-about">
                                <h3 class="footer-title">About Hotel</h3>
                                <p class="footer-about-text">Welcome to the best five-star deluxe hotel in New York. Hotel elementum sesue the aucan vestibulum aliquam justo in sapien rutrum volutpat.</p>
                                
                                <div class="footer-language"> <i class="lni ti-world"></i>
                                    <select onchange="location = this.value;">
                                        <option value="#0">English</option>
                                        <option value="#0">German</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 offset-md-1">
                            <div class="footer-column footer-explore clearfix">
                                <h3 class="footer-title">Explore</h3>
                                <ul class="footer-explore-list list-unstyled">
                                    <li><a href="index.html">Home</a></li>
                                    <li><a href="rooms.html">Rooms & Suites</a></li>
                                    <li><a href="restaurant.html">Restaurant</a></li>
                                    <li><a href="spa-wellness.html">Spa & Wellness</a></li>
                                    <li><a href="about.html">About Hotel</a></li>
                                    <li><a href="contact.html">Contact</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="footer-column footer-contact">
                                <h3 class="footer-title">Contact</h3>
                                <p class="footer-contact-text">1616 Broadway NY, New York 10001<br>United States of America</p>
                                <div class="footer-contact-info">
                                        <p class="footer-contact-phone"><span class="flaticon-call"></span> 855 100 4444</p>
                                        <p class="footer-contact-mail">info@luxuryhotel.com</p>
                                </div>
                                <div class="footer-about-social-list">
                                    <a href="#"><i class="ti-instagram"></i></a>
                                    <a href="#"><i class="fa-brands fa-x-twitter"></i></a>
                                    <a href="#"><i class="ti-youtube"></i></a>
                                    <a href="#"><i class="ti-facebook"></i></a>
                                    <a href="#"><i class="fa-brands fa-tiktok"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="footer-bottom-inner">
                                <p class="footer-bottom-copy-right">© Copyright 2022 by <a href="#">DuruThemes.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </footer>
    <!-- jQuery -->
    <script src="HomePage/js/jquery-3.7.1.min.js"></script>
    <script src="HomePage/js/jquery-migrate-3.5.0.min.js"></script>
    <script src="HomePage/js/modernizr-2.6.2.min.js"></script>
    <script src="HomePage/js/imagesloaded.pkgd.min.js"></script>
    <script src="HomePage/js/jquery.isotope.v3.0.2.js"></script>
    <script src="HomePage/js/pace.js"></script>
    <script src="HomePage/js/popper.min.js"></script>
    <script src="HomePage/js/bootstrap.min.js"></script>
    <script src="HomePage/js/scrollIt.min.js"></script>
    <script src="HomePage/js/jquery.waypoints.min.js"></script>
    <script src="HomePage/js/owl.carousel.min.js"></script>
    <script src="HomePage/js/jquery.stellar.min.js"></script>
    <script src="HomePage/js/jquery.magnific-popup.js"></script>
    <script src="HomePage/js/YouTubePopUp.js"></script>
    <script src="HomePage/js/select2.js"></script>
    <script src="HomePage/js/datepicker.js"></script>
    <script src="HomePage/js/smooth-scroll.min.js"></script>
    <script src="HomePage/js/custom.js"></script>
</body>

<!-- Mirrored from duruthemes.com/demo/html/cappa/demo6-light/room-details.html by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 18 Sep 2025 01:56:18 GMT -->
</html>


