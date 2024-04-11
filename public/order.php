<?php
session_start();
require_once __DIR__ . '/../src/bootstrap.php';

include_once __DIR__ . '/../src/partials/header.php';
require_once __DIR__ . '/../src/db_connect.php';


// Lấy danh sách đơn hàng từ cơ sở dữ liệu kèm thông tin của khách hàng từ bảng thanh_vien
$stmt = $pdo->query("SELECT don_hang.*, thanh_vien.username AS ten_khach_hang, thanh_vien.address, thanh_vien.phone_number
                    FROM don_hang
                    INNER JOIN thanh_vien ON don_hang.thanh_vien_id = thanh_vien.thanh_vien_id");

$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <?php include_once __DIR__ . '/../src/partials/navbar.php' ?>

    <div class="container mt-3">
        <div class="row">
            <div class="col">
                <h1>Duyệt đơn hàng</h1>
                <?php if (empty($orders)) : ?>
                    <p>Không có đơn hàng nào cần duyệt.</p>
                <?php else : ?>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Tên Khách hàng</th>
                                <th scope="col">Ngày lập</th>
                                <th scope="col">Địa chỉ</th>
                                <th scope="col">Số điện thoại</th>
                                <th scope="col">Tổng giá</th>
                                <th scope="col">Chi tiết đơn hàng</th>
                                <th scope="col">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) : ?>
                                <tr>
                                    <td><?= $order['don_hang_id'] ?></td>
                                    <td><?= $order['ten_khach_hang'] ?></td>
                                    <td><?= $order['ngay_lap'] ?></td>
                                    <td><?= $order['address'] ?></td>
                                    <td><?= $order['phone_number'] ?></td>
                                    <td><?= number_format($order['tong_gia'], 0, ',', '.') ?> VNĐ</td>
                                    <td>
                                        <ul>
                                            <?php
                                            // Truy vấn các sản phẩm đã đặt trong đơn hàng
                                            $order_id = $order['don_hang_id'];
                                            $stmt = $pdo->prepare("SELECT san_pham.ten_san_pham, chi_tiet_don_hang.so_luong, chi_tiet_don_hang.chi_tiet_gia
                                                                   FROM chi_tiet_don_hang
                                                                   INNER JOIN san_pham ON chi_tiet_don_hang.san_pham_id = san_pham.san_pham_id
                                                                   WHERE chi_tiet_don_hang.don_hang_id = ?");
                                            $stmt->execute([$order_id]);
                                            $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($order_items as $item) {
                                                echo "<li>{$item['ten_san_pham']} - Số lượng: {$item['so_luong']} - Giá: {$item['chi_tiet_gia']} VNĐ</li>";
                                            }
                                            ?>
                                        </ul>
                                    </td>
                                    <td>
                                    <form action="order_status.php" method="POST" class="mb-2">
                                        <input type="hidden" name="order_id" value="<?= $order['don_hang_id'] ?>">
                                        <label for="order_status">Trạng thái:</label>
                                        <select name="order_status" id="order_status">
                                            <option value="Chưa duyệt" <?= $order['trang_thai'] === 'Chưa duyệt' ? 'selected' : '' ?>>Chưa duyệt</option>
                                            <option value="Đã duyệt" <?= $order['trang_thai'] === 'Đã duyệt' ? 'selected' : '' ?>>Đã duyệt</option>
                                            <option value="Hủy" <?= $order['trang_thai'] === 'Hủy' ? 'selected' : '' ?>>Hủy</option>
                                        </select>
                                        <button type="submit">Cập nhật</button>
                                    </form>
                                    <?php   if (isset($_SESSION['success_message'])) {
                                        echo '<div id="success-message" class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
                                        unset($_SESSION['success_message']);
                                                     }?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
        // set thời gian hiện thông báo
        setTimeout(function() {
            document.getElementById('success-message').style.display = 'none';
        }, 2000);
    </script>
    <?php include_once __DIR__ . '/../src/partials/footer.php' ?>
</body>
</html>
