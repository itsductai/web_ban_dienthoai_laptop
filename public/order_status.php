<?php
require_once __DIR__ . '/../src/db_connect.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['order_status'];

    // Validate $new_status here if needed

    $stmt = $pdo->prepare("UPDATE don_hang SET trang_thai = ? WHERE don_hang_id = ?");
    $stmt->execute([$new_status, $order_id]);
    $_SESSION['success_message'] = "Cập nhật trạng thái đơn hàng thành công";
    // Redirect back to the page displaying orders
    header("Location: order.php");
    exit();
}
?>
