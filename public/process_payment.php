<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    // Nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
    header("Location: login.php");
    exit;
}

// Kiểm tra xem có dữ liệu được gửi từ form không
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Lấy thông tin từ form
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    // Lấy thông tin giỏ hàng từ session
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
   
    // Tính tổng giá trị đơn hàng
    $totalPrice = 0;
    foreach ($cart as $product) {
        $totalPrice += $product[2] * $product[3];
    }
    var_dump($product);
    // Lưu thông tin đơn hàng vào cơ sở dữ liệu
    require_once __DIR__ . '/../src/db_connect.php';

    $query = "INSERT INTO don_hang (thanh_vien_id, ngay_lap, tong_gia) VALUES (?, ?, ?)";
    try {
        $statement = $pdo->prepare($query);
        $statement->execute([$_SESSION['user_id'], date('Y-m-d'), $totalPrice]);
        $orderId = $pdo->lastInsertId(); // Lấy ID của đơn hàng vừa được thêm vào
    } catch (PDOException $e) {
        $error_message = 'Không thể lưu đơn hàng vào cơ sở dữ liệu!';
        // Xử lý lỗi
    }

    // Lưu thông tin chi tiết đơn hàng vào cơ sở dữ liệu
    foreach ($cart as $product) {
        $productId = $product['4'];
        $quantity = $product['3'];
        $price = $product['2'];

        $query = "INSERT INTO chi_tiet_don_hang (don_hang_id, san_pham_id, so_luong, chi_tiet_gia) VALUES (?, ?, ?, ?)";
        try {
            $statement = $pdo->prepare($query);
            $statement->execute([$orderId, $productId, $quantity, $price]);
        } catch (PDOException $e) {
            $error_message = 'Không thể lưu chi tiết đơn hàng vào cơ sở dữ liệu!';
            // Xử lý lỗi
        }
    }

    unset($_SESSION['cart']);

    // Chuyển hướng người dùng sau khi xử lý đơn hàng thành công
    header("Location: index.php");
    exit;
} else {
    // Nếu không có dữ liệu được gửi từ form, chuyển hướng về trang thanh toán
    header("Location: checkout.php");
    exit;
}

?>
