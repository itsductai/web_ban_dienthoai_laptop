<?php
require_once __DIR__ . '/../src/bootstrap.php';
include_once __DIR__ . '/../src/partials/header.php';
use CT275\Project\Product;
require_once __DIR__ . '/../src/bootstrap.php';
$errors = [];
$product = new Product($PDO);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product = new Product($PDO);
    $product->fill($_POST);
    if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'Images/';
        $imagePath = $uploadDir . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
        $product->image = $imagePath;
    }

    if ($product->validate()) {
        $product->save() && redirect('/Admin.php');
    }

    $errors = $product->getValidationErrors();
}

?>

<body>
    <?php include_once __DIR__ . '/../src/partials/navbar.php' ?>

    <!-- Main Page Content -->
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-md-6 mb-2">
                <div class="card mt-5">
                    <div class="card-body">
                        <h5 class="card-title text-center">Thêm sản phẩm</h5>
                        <form method="post" enctype="multipart/form-data">
                            <!-- Name -->
                            <div class="form-group">
                                <label for="name">Tên sản phẩm</label>
                                <input type="text" name="ten_san_pham" class="form-control<?= isset($errors['ten_san_pham']) ? ' is-invalid' : '' ?>" id="name" placeholder="Nhập tên sản phẩm" value="<?= isset($_POST['ten_san_pham']) ? html_escape($_POST['ten_san_pham']) : '' ?>">
                                <?php if (isset($errors['ten_san_pham'])) : ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['ten_san_pham'] ?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea name="mo_ta" class="form-control<?= isset($errors['mo_ta']) ? ' is-invalid' : '' ?>" id="description" placeholder="Nhập mô tả sản phẩm"><?= isset($_POST['mo_ta']) ? html_escape($_POST['mo_ta']) : '' ?></textarea>
                                <?php if (isset($errors['mo_ta'])) : ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['mo_ta'] ?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <!-- Price -->
                            <div class="form-group">
                                <label for="price">Giá</label>
                                <input type="text" name="gia" class="form-control<?= isset($errors['gia']) ? ' is-invalid' : '' ?>" id="price" placeholder="Nhập giá sản phẩm" value="<?= isset($_POST['gia']) ? html_escape($_POST['gia']) : '' ?>">
                                <?php if (isset($errors['gia'])) : ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['gia'] ?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <!-- Quantity -->
                            <div class="form-group">
                                <label for="quantity">Số lượng</label>
                                <input type="text" name="so_luong" class="form-control<?= isset($errors['so_luong']) ? ' is-invalid' : '' ?>" id="quantity" placeholder="Nhập số lượng sản phẩm" value="<?= isset($_POST['so_luong']) ? html_escape($_POST['so_luong']) : '' ?>">
                                <?php if (isset($errors['so_luong'])) : ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['so_luong'] ?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <!-- Image -->
                            <div class="form-group">
                                <label for="image">Hình ảnh:</label>
                                <input type="file" name="image" accept="image/*" class="form-control-file<?= isset($errors['image']) ? ' is-invalid' : '' ?>" id="image">
                                <?php if (isset($errors['image'])) : ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['image'] ?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <!-- Category -->
                            <div class="form-group">
                                <label for="category">Danh mục</label>
                                <select name="danh_muc_id" class="form-control<?= isset($errors['danh_muc_id']) ? ' is-invalid' : '' ?>" id="category">
                                    <option value="">Chọn danh mục</option>
                                    <?php
                                    $categories = $product->getAllCategories(); // Lấy danh sách các danh mục từ cơ sở dữ liệu
                                        foreach ($categories as $category) {
                                            echo '<option value="' . $category['danh_muc_id'] . '">' . $category['ten_danh_muc'] . '</option>'; // Hiển thị danh sách các danh mục trong phần tử select
                                        }
                                        ?>
                                </select>
                                <?php if (isset($errors['danh_muc_id'])) : ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['danh_muc_id'] ?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <!-- Submit -->
                            <div class="text-center">
                                <button type="submit" name="submit" class="btn btn-primary">Thêm sản phẩm</button>
                            </div>
                        </form>
                      
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include_once __DIR__ . '/../src/partials/footer.php' ?>

</body>

</html>
