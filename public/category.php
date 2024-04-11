<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/partials/header.php';
require_once __DIR__ . '/../src/db_connect.php';

$errors = []; // Mảng lưu trữ các lỗi nếu có
$success_message = ''; // Thông báo thành công

// Kiểm tra xem có yêu cầu sửa đổi danh mục không
if (isset($_GET['edit']) && is_numeric($_GET['edit'])) {
    $edit_id = $_GET['edit'];

    // Kiểm tra xem danh mục có tồn tại không
    $stmt = $pdo->prepare("SELECT * FROM danh_muc WHERE danh_muc_id = ?");
    $stmt->execute([$edit_id]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$category) {
        // Nếu danh mục không tồn tại, hiển thị thông báo lỗi
        $errors[] = 'Không tìm thấy danh mục để sửa.';
    }
}

// Xử lý dữ liệu khi người dùng submit form sửa danh mục
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Kiểm tra xem người dùng đã nhấn nút "Hủy" hay không
    if (isset($_POST['cancel'])) {
        header("Location: /category.php"); // Chuyển hướng về trang danh mục
        exit();
    }

    // Kiểm tra xem người dùng đã nhập tên danh mục hay chưa
    if (isset($_POST['edit_category'])) {
        if (!empty($_POST['ten_danh_muc'])) {
            $ten_danh_muc = $_POST['ten_danh_muc'];

            // Cập nhật tên danh mục trong cơ sở dữ liệu
            $query = "UPDATE danh_muc SET ten_danh_muc = :ten_danh_muc WHERE danh_muc_id = :edit_id";
            $statement = $pdo->prepare($query);
            $statement->bindParam(':ten_danh_muc', $ten_danh_muc, PDO::PARAM_STR);
            $statement->bindParam(':edit_id', $edit_id, PDO::PARAM_INT);

            if ($statement->execute()) {
                $success_message = 'Cập nhật tên danh mục thành công!';
            } else {
                $errors[] = 'Đã xảy ra lỗi khi cập nhật tên danh mục. Vui lòng thử lại sau.';
            }
        } else {
            $errors[] = 'Vui lòng nhập tên danh mục.';
        }
    }
}

// Truy vấn danh sách danh mục từ cơ sở dữ liệu
$query = "SELECT danh_muc_id, ten_danh_muc FROM danh_muc";
$stmt = $pdo->query($query);
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<body>
    <?php require_once __DIR__ . '/../src/partials/navbar.php'; ?>

    <div class="container mt-3">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-3">Danh mục sản phẩm</h2>

                <!-- Hiển thị thông báo thành công nếu có -->
                <?php if ($success_message) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= $success_message ?>
                    </div>
                <?php endif; ?>

                <!-- Form sửa danh mục -->
                <?php if (isset($_GET['edit']) && is_numeric($_GET['edit'])) : ?>
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <form method="post" class="mb-3">
                                <div class="mb-3">
                                    <label for="ten_danh_muc" class="form-label">Tên danh mục</label>
                                    <input type="text" class="form-control" id="ten_danh_muc" name="ten_danh_muc" value="<?= $category['ten_danh_muc'] ?? ''; ?>" required>
                                </div>
                                <input type="hidden" name="edit_category" value="1">
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                                <button type="submit" class="btn btn-secondary" name="cancel">Hủy</button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>


                <!-- Hiển thị danh sách danh mục -->
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">ID Danh mục</th>
                            <th scope="col">Tên Danh mục</th>
                            <th scope="col">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category) : ?>
                            <tr>
                                <td><?= $category['danh_muc_id']; ?></td>
                                <td><?= $category['ten_danh_muc']; ?></td>
                                <td>
                                    <a href="?edit=<?= $category['danh_muc_id']; ?>" class="btn btn-sm btn-primary">Sửa</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <!-- Form thêm mới danh mục -->
                <div class="card mb-3 border">
                    <div class="card-body">
                        <h3>Thêm mới danh mục</h3>
                        <form method="post">
                            <div class="mb-3">
                                <label for="ten_danh_muc_moi" class="form-label">Tên danh mục mới</label>
                                <input type="text" class="form-control" id="ten_danh_muc_moi" name="ten_danh_muc_moi" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Thêm mới</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php require_once __DIR__ . '/../src/partials/footer.php'; ?>
</body>
</html>
