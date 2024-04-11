<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập hay chưa

// Lấy thông tin thanh toán từ CSDL
require_once __DIR__ . '/../src/db_connect.php';

$query = "SELECT * FROM thanh_vien WHERE thanh_vien_id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$_SESSION['user_id']]);
$user = $statement->fetch(PDO::FETCH_ASSOC);
// Lấy thông tin đơn hàng từ giỏ hàng
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$total = 0;

?>

<?php require_once __DIR__ . '/../src/bootstrap.php'; ?>
<?php include_once __DIR__ . '/../src/partials/header.php'; ?>

<body>
    <?php include_once __DIR__ . '/../src/partials/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Thông tin đơn hàng</h2>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col">STT</th>
                                        <th scope="col">Tên sản phẩm</th>
                                        <th scope="col">Số lượng</th>
                                        <th scope="col">Giá</th>
                                        <th scope="col">Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($cart as $index => $product) : ?>
                                        <tr>
                                            <td><?= $index + 1 ?></td>
                                            <td><?= $product[1] ?></td>
                                            <td><?= $product[3] ?></td>
                                            <td><?= number_format($product[2], 0, ',', '.') ?> VNĐ</td>
                                            <td><?= number_format($product[2] * $product[3], 0, ',', '.') ?> VNĐ</td>
                                        </tr>
                                        <?php $total += $product[2] * $product[3]; ?>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                                        <td><?= number_format($total, 0, ',', '.') ?> VNĐ</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title">Thông tin thanh toán</h2>
                        <form action="process_payment.php" method="POST">
                            <div class="mb-3">
                                <label for="name" class="form-label">Họ và tên:</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= $user['username'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= $user['email'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại:</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="<?= $user['phone_number'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Địa chỉ:</label>
                                <textarea class="form-control" id="address" name="address" rows="3" required><?= $user['address'] ?></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../src/partials/footer.php'; ?>
</body>

</html>
