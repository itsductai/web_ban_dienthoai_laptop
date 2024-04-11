<?php
require_once __DIR__ . '/../src/bootstrap.php';
require_once __DIR__ . '/../src/classes/Product.php';

use CT275\Project\Product;
$product = new Product($PDO);
$id = isset($_REQUEST['id']) ?
    filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT) : -1;
if ($id < 0 || !($product->find($id))) {
    redirect('/Admin.php');
    }
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($product->update($_POST)) {
    // Cập nhật dữ liệu thành công
        redirect('/Admin.php');
    }
    // Cập nhật dữ liệu không thành công
    $errors = $product->getValidationErrors();
}
include_once __DIR__ . '/../src/partials/header.php';
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
                                <input type="text" name="ten_san_pham" class="form-control<?= isset($errors['ten_san_pham']) ? ' is-invalid' : '' ?>" id="name" placeholder="Nhập tên sản phẩm" value="<?= html_escape($product->name) ?>" />

                                <?php if (isset($errors['ten_san_pham'])) : ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['ten_san_pham'] ?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <!-- Description -->
                            <div class="form-group">
                                <label for="description">Mô tả</label>
                                <textarea name="mo_ta" class="form-control<?= isset($errors['mo_ta']) ? ' is-invalid' : '' ?>" id="description" placeholder="Nhập mô tả sản phẩm"><?=html_escape($product->description)?></textarea>
                                <?php if (isset($errors['mo_ta'])) : ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['mo_ta'] ?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <!-- Price -->
                            <div class="form-group">
                                <label for="price">Giá</label>
                                <input type="text" name="gia" class="form-control<?= isset($errors['gia']) ? ' is-invalid' : '' ?>" id="price" placeholder="Nhập giá sản phẩm" value="<?=html_escape($product->price)?>">
                                <?php if (isset($errors['gia'])) : ?>
                                    <div class="invalid-feedback">
                                        <?= $errors['gia'] ?>
                                    </div>
                                <?php endif ?>
                            </div>
                            <!-- Quantity -->
                            <div class="form-group">
                                <label for="quantity">Số lượng</label>
                                <input type="text" name="so_luong" class="form-control<?= isset($errors['so_luong']) ? ' is-invalid' : '' ?>" id="quantity" placeholder="Nhập số lượng sản phẩm" value="<?=html_escape($product->quantity)?>">
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
                                    <option>Chọn danh mục</option>
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
                                <button type="submit" name="submit" class="btn btn-primary">Cập nhật sản phẩm</button>
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