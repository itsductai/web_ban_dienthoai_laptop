<?php
// Khởi động session
session_start();

// Hủy bỏ tất cả các biến phiên làm việc
$_SESSION = array();

// Hủy bỏ phiên làm việc
session_destroy();

// Chuyển hướng về trang chính
header("Location: index.php");
exit;
?>
