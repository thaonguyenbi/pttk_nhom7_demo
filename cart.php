<?php
include 'database/conn.php';
session_start();

$tongTien = 0;

// Thêm sản phẩm vào giỏ hàng
if (isset($_POST['addtocartbtn'])) {
  // Lấy thông tin sản phẩm từ form
  $MaSP = $_POST['masp'];
  $soluong = $_POST['soluong'];

  // Lấy thông tin sản phẩm từ bảng sanpham
  $productQuery = "SELECT * FROM sanpham WHERE MaSP = '$MaSP'";
  $productResult = mysqli_query($conn, $productQuery);
  $product = mysqli_fetch_assoc($productResult);

  $dongia = $product['DonGia']; // Đơn giá
  $tensp = $product['TenSanPham']; //Tên sản phẩm

  // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
  $checkQuery = "SELECT * FROM chitietgiohang WHERE MaSP = '$MaSP'";
  $checkResult = mysqli_query($conn, $checkQuery);

  if (mysqli_num_rows($checkResult) > 0) {
    // Nếu sản phẩm đã có trong giỏ hàng, cập nhật số lượng
    $row = mysqli_fetch_assoc($checkResult);
    $newQuantity = $row['SoLuong'] + $soluong; // Tăng số lượng

    // Cập nhật sản phẩm trong giỏ hàng
    $updateQuery = "UPDATE chitietgiohang SET SoLuong = '$newQuantity' WHERE MaSP = '{$row['MaSP']}'";
    mysqli_query($conn, $updateQuery);
  } else {
    // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới sản phẩm
    $insertQuery = "INSERT INTO chitietgiohang (MaSP, SoLuong) VALUES ('$MaSP', '$soluong')";
    mysqli_query($conn, $insertQuery); // Thực hiện thêm sản phẩm vào giỏ hàng
  }
}

// Cập nhật số lượng sản phẩm trong giỏ hàng
if (isset($_POST['updateQuantity'])) {
  $MaSP = $_POST['masp']; // Lấy mã sản phẩm
  $action = $_POST['updateQuantity']; // Nhận hành động: "plus" hoặc "minus"

  // Lấy số lượng hiện tại từ bảng giỏ hàng
  $query = "SELECT SoLuong FROM chitietgiohang WHERE MaSP = '$MaSP'";
  $result = mysqli_query($conn, $query);
  $row = mysqli_fetch_assoc($result);
  $currentQuantity = $row['SoLuong'];

  // Xử lý tăng hoặc giảm số lượng
  if ($action === 'plus') {
    $newQuantity = $currentQuantity + 1; // Tăng số lượng
  } elseif ($action === 'minus' && $currentQuantity > 1) {
    $newQuantity = $currentQuantity - 1; // Giảm số lượng (không dưới 1)
  } else {
    $newQuantity = $currentQuantity; // Không thay đổi nếu số lượng = 1 và nhấn giảm
  }

  // Cập nhật số lượng trong bảng giỏ hàng
  $updateQuery = "UPDATE chitietgiohang SET SoLuong = '$newQuantity' WHERE MaSP = '$MaSP'";
  mysqli_query($conn, $updateQuery);
}


// Xóa sản phẩm khỏi giỏ hàng
if (isset($_POST['remove']) && isset($_POST['MaSP'])) {
  // Lấy mã sản phẩm cần xóa
  $MaSPToRemove = $_POST['MaSP'];

  // Xóa sản phẩm khỏi bảng chitietgiohang
  $deleteQuery = "DELETE FROM chitietgiohang WHERE MaSP = '$MaSPToRemove'";
  mysqli_query($conn, $deleteQuery); // Thực hiện xóa sản phẩm
}

// Lấy chi tiết giỏ hàng để hiển thị các sản phẩm
$cartDetailsResult = mysqli_query($conn, "SELECT chitietgiohang.*, sanpham.TenSanPham, sanpham.DonGia
FROM chitietgiohang
JOIN sanpham ON chitietgiohang.MaSP = sanpham.MaSP
ORDER BY chitietgiohang.MaCTGH ASC");

while ($cartDetailsRow = mysqli_fetch_assoc($cartDetailsResult)) {
  $thanhTien = $cartDetailsRow['DonGia'] * $cartDetailsRow['SoLuong']; // Tính thành tiền
  $tongTien += $thanhTien; // Cộng thành tiền vào tổng tiền
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <title>Maycha</title>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="format-detection" content="telephone=no">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="author" content="">
  <meta name="keywords" content="">
  <meta name="description" content="">

  <!-- Favicon -->
  <link href="img/favicon.ico" rel="icon">
  <!-- Google Web Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Libraries Stylesheet -->
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
    integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&family=Open+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">

  <!-- Google Web Fonts -->
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

  <!-- Libraries Stylesheet -->
  <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

  <!-- Customized Bootstrap Stylesheet -->
  <link href="css/cart.css" rel="stylesheet">
</head>

<body>


  <!-- Navbar Start -->

  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <defs>
      <symbol xmlns="http://www.w3.org/2000/svg" id="link" viewBox="0 0 24 24">
        <path fill="currentColor" d="M12 19a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0-4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm-5 0a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm7-12h-1V2a1 1 0 0 0-2 0v1H8V2a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v14a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V6a3 3 0 0 0-3-3Zm1 17a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-9h16Zm0-11H4V6a1 1 0 0 1 1-1h1v1a1 1 0 0 0 2 0V5h8v1a1 1 0 0 0 2 0V5h1a1 1 0 0 1 1 1ZM7 15a1 1 0 1 0-1-1a1 1 0 0 0 1 1Zm0 4a1 1 0 1 0-1-1a1 1 0 0 0 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="arrow-right" viewBox="0 0 24 24">
        <path fill="currentColor" d="M17.92 11.62a1 1 0 0 0-.21-.33l-5-5a1 1 0 0 0-1.42 1.42l3.3 3.29H7a1 1 0 0 0 0 2h7.59l-3.3 3.29a1 1 0 0 0 0 1.42a1 1 0 0 0 1.42 0l5-5a1 1 0 0 0 .21-.33a1 1 0 0 0 0-.76Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="category" viewBox="0 0 24 24">
        <path fill="currentColor" d="M19 5.5h-6.28l-.32-1a3 3 0 0 0-2.84-2H5a3 3 0 0 0-3 3v13a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3v-10a3 3 0 0 0-3-3Zm1 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-13a1 1 0 0 1 1-1h4.56a1 1 0 0 1 .95.68l.54 1.64a1 1 0 0 0 .95.68h7a1 1 0 0 1 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="calendar" viewBox="0 0 24 24">
        <path fill="currentColor" d="M19 4h-2V3a1 1 0 0 0-2 0v1H9V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7h16Zm0-9H4V7a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V6h6v1a1 1 0 0 0 2 0V6h2a1 1 0 0 1 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="heart" viewBox="0 0 24 24">
        <path fill="currentColor" d="M20.16 4.61A6.27 6.27 0 0 0 12 4a6.27 6.27 0 0 0-8.16 9.48l7.45 7.45a1 1 0 0 0 1.42 0l7.45-7.45a6.27 6.27 0 0 0 0-8.87Zm-1.41 7.46L12 18.81l-6.75-6.74a4.28 4.28 0 0 1 3-7.3a4.25 4.25 0 0 1 3 1.25a1 1 0 0 0 1.42 0a4.27 4.27 0 0 1 6 6.05Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="plus" viewBox="0 0 24 24">
        <path fill="currentColor" d="M19 11h-6V5a1 1 0 0 0-2 0v6H5a1 1 0 0 0 0 2h6v6a1 1 0 0 0 2 0v-6h6a1 1 0 0 0 0-2Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="minus" viewBox="0 0 24 24">
        <path fill="currentColor" d="M19 11H5a1 1 0 0 0 0 2h14a1 1 0 0 0 0-2Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="cart" viewBox="0 0 24 24">
        <path fill="currentColor" d="M8.5 19a1.5 1.5 0 1 0 1.5 1.5A1.5 1.5 0 0 0 8.5 19ZM19 16H7a1 1 0 0 1 0-2h8.491a3.013 3.013 0 0 0 2.885-2.176l1.585-5.55A1 1 0 0 0 19 5H6.74a3.007 3.007 0 0 0-2.82-2H3a1 1 0 0 0 0 2h.921a1.005 1.005 0 0 1 .962.725l.155.545v.005l1.641 5.742A3 3 0 0 0 7 18h12a1 1 0 0 0 0-2Zm-1.326-9l-1.22 4.274a1.005 1.005 0 0 1-.963.726H8.754l-.255-.892L7.326 7ZM16.5 19a1.5 1.5 0 1 0 1.5 1.5a1.5 1.5 0 0 0-1.5-1.5Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="check" viewBox="0 0 24 24">
        <path fill="currentColor" d="M18.71 7.21a1 1 0 0 0-1.42 0l-7.45 7.46l-3.13-3.14A1 1 0 1 0 5.29 13l3.84 3.84a1 1 0 0 0 1.42 0l8.16-8.16a1 1 0 0 0 0-1.47Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="trash" viewBox="0 0 24 24">
        <path fill="currentColor" d="M10 18a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1ZM20 6h-4V5a3 3 0 0 0-3-3h-2a3 3 0 0 0-3 3v1H4a1 1 0 0 0 0 2h1v11a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8h1a1 1 0 0 0 0-2ZM10 5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v1h-4Zm7 14a1 1 0 0 1-1 1H8a1 1 0 0 1-1-1V8h10Zm-3-1a1 1 0 0 0 1-1v-6a1 1 0 0 0-2 0v6a1 1 0 0 0 1 1Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="star-outline" viewBox="0 0 15 15">
        <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" d="M7.5 9.804L5.337 11l.413-2.533L4 6.674l2.418-.37L7.5 4l1.082 2.304l2.418.37l-1.75 1.793L9.663 11L7.5 9.804Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="star-solid" viewBox="0 0 15 15">
        <path fill="currentColor" d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="search" viewBox="0 0 24 24">
        <path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="user" viewBox="0 0 24 24">
        <path fill="currentColor" d="M15.71 12.71a6 6 0 1 0-7.42 0a10 10 0 0 0-6.22 8.18a1 1 0 0 0 2 .22a8 8 0 0 1 15.9 0a1 1 0 0 0 1 .89h.11a1 1 0 0 0 .88-1.1a10 10 0 0 0-6.25-8.19ZM12 12a4 4 0 1 1 4-4a4 4 0 0 1-4 4Z" />
      </symbol>
      <symbol xmlns="http://www.w3.org/2000/svg" id="close" viewBox="0 0 15 15">
        <path fill="currentColor" d="M7.953 3.788a.5.5 0 0 0-.906 0L6.08 5.85l-2.154.33a.5.5 0 0 0-.283.843l1.574 1.613l-.373 2.284a.5.5 0 0 0 .736.518l1.92-1.063l1.921 1.063a.5.5 0 0 0 .736-.519l-.373-2.283l1.574-1.613a.5.5 0 0 0-.283-.844L8.921 5.85l-.968-2.062Z" />
      </symbol>
    </defs>
  </svg>

  <div class="preloader-wrapper">
    <div class="preloader">
    </div>
  </div>


  <header>
    <div class="container-fluid">
      <div class="row py-3 border-bottom">

        <div class="col-sm-4 col-lg-3 text-center text-sm-start">
          <div class="main-logo">
            <a href="index.php">
              <img src="images/logo.png" alt="logo" class="img-fluid" width="20%">
            </a>
          </div>
        </div>

        <div class="col-sm-6 offset-sm-2 offset-md-0 col-lg-5 d-none d-lg-block">
          <div class="search-bar row bg-light p-2 my-2 rounded-4">
            <div class="col-1">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                <path fill="currentColor" d="M21.71 20.29L18 16.61A9 9 0 1 0 16.61 18l3.68 3.68a1 1 0 0 0 1.42 0a1 1 0 0 0 0-1.39ZM11 18a7 7 0 1 1 7-7a7 7 0 0 1-7 7Z" />
              </svg>
            </div>
            <div class="col-11 col-md-7">
              <form id="search-form" class="text-center" action="index.html" method="post">
                <input type="text" class="form-control border-0 bg-transparent" placeholder="Tìm kiếm" />
              </form>
            </div>
          </div>
        </div>

        <div class="col-sm-8 col-lg-4 d-flex justify-content-end gap-5 align-items-center mt-4 mt-sm-0 justify-content-center justify-content-sm-end">


          <ul class="d-flex justify-content-end list-unstyled m-0">
            <li>
              <a href="#" class="rounded-circle bg-light p-2 mx-1">
                <svg width="24" height="24" viewBox="0 0 24 24">
                  <use xlink:href="#heart"></use>
                </svg>
              </a>
            </li>

            <li class="d-lg-none">
              <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
                <svg width="24" height="24" viewBox="0 0 24 24">
                  <use xlink:href="#cart"></use>
                </svg>
              </a>
            </li>
            <li class="d-lg-none">
              <a href="#" class="rounded-circle bg-light p-2 mx-1" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSearch" aria-controls="offcanvasSearch">
                <svg width="24" height="24" viewBox="0 0 24 24">
                  <use xlink:href="#search"></use>
                </svg>
              </a>
            </li>

          </ul>

          <div class="cart text-end d-none d-lg-block dropdown">
            <button class="border-0 bg-transparent d-flex flex-column gap-2 lh-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasCart" aria-controls="offcanvasCart">
              <span class="fs-6 text-muted dropdown-toggle">Giỏ hàng</span>
              <span class="cart-total fs-5 fw-bold"><?= number_format($tongTien, 0, ',', '.') ?>đ</span>
            </button>
          </div>
        </div>

      </div>
    </div>
    <div class="container-fluid">
      <div class="row py-3">
        <div class="d-flex justify-content-center align-items-center" style="background-color: #580323;">
          <nav class="main-menu d-flex navbar navbar-expand-lg align-content-center">

            <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar"
              aria-controls="offcanvasNavbar">
              <span class="navbar-toggler-icon"></span>
            </button>

            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">

              <div class="offcanvas-header justify-content-center">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
              </div>

              <div class="offcanvas-body">

                <select class="filter-categories border-0 mb-0 me-5 text-light" style="background-color: #580323;">
                  <option>Trang chủ</option>
                  <option>Trang chủ</option>
                </select>

                <ul class="navbar-nav justify-content-end menu-list list-unstyled d-flex gap-md-3 mb-0">
                  <li class="nav-item active">
                    <a href="#gioithieu" class="nav-link text-light">Giới thiệu</a>
                  </li>
                  <li class="nav-item dropdown">
                    <a href="#sanpham" class="nav-link text-light">Sản phẩm</a>
                  </li>
                  <li class="nav-item">
                    <a href="#lienhe" class="nav-link text-light">Liên hệ</a>
                  </li>
                  <li class="nav-item">
                    <a href="#dangky" class="nav-link text-light">Đăng ký</a>
                  </li>
                  <li class="nav-item">
                    <a href="#dangnhap" class="nav-link text-light">Đăng nhập</a>
                  </li>

                </ul>

              </div>

            </div>
        </div>
      </div>
    </div>
  </header>
  <!-- Navbar End -->


  <!-- Page Header Start -->
  <div class="container-fluid mb-5" style=" background-color: rgba(177, 31, 78, 0.1)">
    <div class="d-flex flex-column align-items-center justify-content-center" style="min-height: 300px">
      <h1 class="font-weight-semi-bold text-uppercase mb-3">GIỎ HÀNG</h1>
      <div class="d-inline-flex">
        <p class="m-0"><a href="index.php"><i class="fa fa-home"></i></a></p>
        <p class="m-0 px-2">></p>
        <p class="m-0"><a href="cart.php" style="color:#580323">Giỏ hàng</a></p>
      </div>
    </div>
  </div>
  <!-- Page Header End -->


  <!-- Cart Start -->

  <div class="container-fluid pt-5">
    <div class="row px-xl-5">
      <div class="col-lg-12 table-responsive mb-5">
        <table class="table table-bordered text-center mb-0">
          <thead class="text-dark">
            <tr>
              <th>SẢN PHẨM</th>
              <th>GIÁ</th>
              <th>SỐ LƯỢNG</th>
              <th>TỔNG</th>
              <th>XÓA</th>
            </tr>
          </thead>
          <tbody class="align-middle">
            <?php if ($tongTien > 0) { ?> <!-- Kiểm tra xem giỏ hàng có sản phẩm không -->
              <?php
              // Lấy chi tiết giỏ hàng để hiển thị các sản phẩm
              $cartDetailsResult = mysqli_query($conn, "SELECT chitietgiohang.*, sanpham.TenSanPham, sanpham.DonGia FROM chitietgiohang
              JOIN sanpham ON chitietgiohang.MaSP = sanpham.MaSP
              ORDER BY chitietgiohang.MaCTGH ASC");
              ?>
              <?php while ($cartDetailsRow = mysqli_fetch_assoc($cartDetailsResult)) { ?> <!-- Lặp qua các sản phẩm trong giỏ hàng -->
                <tr>
                  <td class="align-middle">
                    <div style="display: flex; align-items: center;">
                      <img src="images/thumb-product.png" style="width: 100px; margin-right: 10px;">
                      <span><?= htmlspecialchars($cartDetailsRow['TenSanPham']) ?></span>
                    </div>
                  </td>
                  <td class="align-middle">
                    <!-- Hiển thị chỉ giá sản phẩm -->
                    <?= number_format($cartDetailsRow['DonGia'], 0, ',', '.') ?>đ
                  </td>
                  <td class="align-middle">
                    <form method="post" action="cart.php">
                      <div class="input-group quantity mx-auto" style="width: 100px;">
                        <div class="input-group-btn">
                          <button class="btn btn-sm btn-primary btn-minus" name="updateQuantity" value="minus">
                            <i class="fa fa-minus"></i>
                          </button>
                        </div>
                        <input type="hidden" name="masp" value="<?= $cartDetailsRow['MaSP'] ?>">
                        <input type="text" name="soluong" class="form-control form-control-sm bg-secondary text-center"
                          value="<?= $cartDetailsRow['SoLuong'] ?>" min="1" readonly />
                        <div class="input-group-btn">
                          <button class="btn btn-sm btn-primary btn-plus" name="updateQuantity" value="plus">
                            <i class="fa fa-plus"></i>
                          </button>
                        </div>
                      </div>
                    </form>

                  </td>

                  <td class="align-middle">
                    <!-- Tính và hiển thị thành tiền -->
                    <?php
                    $thanhTien = $cartDetailsRow['DonGia'] * $cartDetailsRow['SoLuong'];
                    echo number_format($thanhTien, 0, ',', '.') . 'đ';
                    ?>
                  </td>
                  <td class="align-middle">
                    <form method="POST" action="">
                      <input type="hidden" name="MaSP" value="<?= htmlspecialchars($cartDetailsRow['MaSP']) ?>"> <!-- Lưu mã sản phẩm để xóa -->
                      <button class="btn btn-sm btn-primary" type="submit" name="remove">
                        <i class="fa fa-times"></i>
                      </button>
                    </form>

                  </td>
                </tr>
              <?php } ?>
          </tbody>
        </table>
        <!-- Section for Total and Button -->
        <div style="display: flex; flex-direction: row; justify-content: flex-end; align-items: center; margin-top: 20px;">
          <div style="display: flex; align-items: center;">
            <span style="font-size: 20px; font-weight: bold; margin-right: 20px;">
              Tổng: <span style="color: #B11F4E;"><?= number_format($tongTien, 0, ',', '.') ?>đ</span>
            </span>
            <form method="post" action="order.php">
              <button name="checkout" class="btn btn-xxl"
                style="background-color: #B11F4E; color: #FFFFFF; font-size: 20px; padding: 12px 40px; border-radius: 1px; font-weight: bold;">
                Mua
              </button>
            </form>
          </div>
        </div>
      </div>
    <?php } else { ?>
      <p>Giỏ hàng của bạn trống.</p> <!-- Thông báo nếu giỏ hàng trống -->
    <?php } ?>
    </div>
    <!-- Cart End -->


    <!-- Footer Start -->
    <footer class="py-5" style="background-color: #580323;">
      <div class="container-fluid">
        <div class="row">

          <div class="col-lg-3 col-md-6 col-sm-6">
            <div class="footer-menu">
              <img src="images/logo-footer.png" alt="logo" width="70%">

            </div>
          </div>

          <div class="col-md-2 col-sm-6 text-light">
            <div class="footer-menu ">
              <h5 class="text-uppercase" style="color:white">Liên kết nhanh</h5>
              <ul class="menu-list list-unstyled">
                <li class="menu-item">
                  <a href="#" class="nav-link">Trang chủ</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Giới thiệu</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Sản phẩm</a>
                </li>
                <li class="menu-item">
                  <a href="#" class="nav-link">Liên hệ</a>
                </li>
              </ul>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="footer-menu">
              <h5 class="text-uppercase" style="color:white">Thông tin liên hệ</h5>
              <ul class="menu-list list-unstyled">
                <li class="menu-item">
                  Địa chỉ: 21 Rạch Bùng Binh, Phường 10, Quận 3, TP. Hồ Chí Minh
                </li>
                <li class="menu-item">
                  Hotline: 0878 808 808
                </li>
                <li class="menu-item">
                  Email: maychaxinchao@maycha.com.vn
              </ul>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="footer-menu">
              <h5 class="text-uppercase" style="color:white">Địa chỉ công ty</h5>
              <ul class="menu-list list-unstyled">
                <li class="menu-item">
                  CÔNG TY CỔ PHẦN MAYCHA
                </li>
                <li class="menu-item">
                  38 Trịnh Đình Trọng, Phường Phú Trung, Quận Tân Phú, Thành phố Hồ Chí Minh, Việt Nam
                </li>
                <li class="menu-item">
                  MST: 0317701572
                </li>
                <li class="menu-item">
                  Hotline: 0878 808 808
                </li>
                <li class="menu-item">
                  Email: maychaxinchao@maycha.com.vn
                </li>

              </ul>
            </div>
          </div>

        </div>
      </div>
    </footer>
    <!-- Footer End -->
    <div id="footer-bottom" style="background-color: #B11F4E;">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-6 copyright">
            <p class="text-light">MAYCHA © 2024. All rights reserved.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>
    <script src="js/jquery-1.11.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
    <script src="js/plugins.js"></script>
    <script src="js/script.js"></script>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>


    <!-- Contact Javascript File -->
    <script src="mail/jqBootstrapValidation.min.js"></script>
    <script src="mail/contact.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>

</body>

</html>