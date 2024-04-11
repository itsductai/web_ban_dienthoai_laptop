<?php
require_once __DIR__ . '/../src/bootstrap.php';
define('TITLE', 'Đăng ký');
include_once __DIR__ . '/../src/partials/header.php';
include_once __DIR__ . '/../src/partials/navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['username']) && !empty($_POST['password']) && !empty($_POST['email']) && !empty($_POST['address']) && !empty($_POST['phone_number'])) {
      require_once __DIR__ . '/../src/db_connect.php';

        $query = "INSERT INTO thanh_vien (username, password, email, address, phone_number) VALUES (?, ?, ?, ?, ?)";
        try {
            $statement = $pdo->prepare($query);
            $statement->execute([
              $_POST['username'], 
              $_POST['password'],
              $_POST['email'],
              $_POST['address'],
              $_POST['phone_number']
          ]);
        } catch (PDOException $e) {
            $pdo_error = $e->getMessage();
        }

        if ($statement && $statement->rowCount() == 1) {
          echo '<p>Đăng ký thành công</p>';
        } else {
            $error_message = 'Không thể thực hiện đăng ký vì ' . ($pdo_error ?? 'lỗi không xác định');
        }
    } else {
        $error_message = 'Vui lòng điền đầy đủ thông tin đăng ký!';
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card mt-5 mb-5">
                <div class="card-body">
                    <h2 class="card-title mb-3 text-center font-weight-bold">ĐĂNG KÝ</h2>
                    <form action="signup.php" method="post">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên người dùng <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ giao hàng <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="phone_number" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control" id="phone_number" name="phone_number" required>
                        </div>
                        <input type="submit" name="submit" class="btn btn-primary" value="Đăng ký">
                        <a href="#" class="btn btn-warning mx-2">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include_once __DIR__ . '/../src/partials/footer.php';
?>
