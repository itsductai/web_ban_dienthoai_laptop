<?php
session_start();

// Kiểm tra xem người dùng đã đăng nhập hay chưa
if (!isset($_SESSION['user_id'])) {
    // Chuyển hướng người dùng về trang đăng nhập
    header("Location: login.php");
    exit();
}

// Lấy thông tin thanh toán từ CSDL
require_once __DIR__ . '/../src/db_connect.php';

$query = "SELECT * FROM thanh_vien WHERE thanh_vien_id = ?";
$statement = $pdo->prepare($query);
$statement->execute([$_SESSION['user_id']]);

$user = $statement->fetch(PDO::FETCH_ASSOC);

// Khai báo biến thông báo
$message = '';

// Xử lý khi người dùng gửi biểu mẫu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Cập nhật thông tin thanh toán vào CSDL
    $query = "UPDATE thanh_vien SET email = ?, phone_number = ?, address = ? WHERE thanh_vien_id = ?";
    $statement = $pdo->prepare($query);
    if ($statement->execute([$email, $phone, $address, $_SESSION['user_id']])) {
        // Nếu cập nhật thành công, thiết lập thông báo và chuyển hướng về trang thanh toán
        $message = "Thay đổi thông tin thanh toán thành công!";
        header("Refresh: 2; URL=checkout.php");
    } else {
        // Nếu cập nhật không thành công, thông báo lỗi
        $message = "Có lỗi xảy ra, vui lòng thử lại sau.";
    }
}
?>

<?php require_once __DIR__ . '/../src/bootstrap.php'; ?>
<?php include_once __DIR__ . '/../src/partials/header.php'; ?>

<body>
    <?php include_once __DIR__ . '/../src/partials/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="card-title">Chỉnh sửa thông tin thanh toán</h2>
                        <?php if (!empty($message)) : ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo $message; ?>
                            </div>
                        <?php endif; ?>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
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
                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../src/partials/footer.php'; ?>
</body>

</html>
