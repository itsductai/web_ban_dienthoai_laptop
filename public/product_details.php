<?php
// Tải các tệp cần thiết và khởi tạo đối tượng sản phẩm
require_once __DIR__ . '/../src/bootstrap.php';
include_once __DIR__ . '/../src/partials/header.php';
use CT275\Project\Product;
$product = new Product($PDO);

// Kiểm tra xem có tồn tại tham số id trong URL không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    // Nếu không, thông báo lỗi và kết thúc script
    echo 'Lỗi: Không tìm thấy ID sản phẩm.';
    exit;
}

// Lấy ID sản phẩm từ URL
$product_id = $_GET['id'];

// Lấy thông tin chi tiết sản phẩm dựa trên ID
$product_details = $product->find($product_id);

// Kiểm tra xem sản phẩm có tồn tại không
if (!$product_details) {
    // Nếu không, thông báo lỗi và kết thúc script
    echo 'Sản phẩm không tồn tại.';
    exit;
}
include_once __DIR__ . '/../src/partials/navbar.php';
?>

<!-- Hiển thị thông tin chi tiết sản phẩm -->
<div class="container mt-4">
    
    <div class="row justify-content-center">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $product_details->name; ?></li>
                </ol>
            </nav>
            <div class="card shadow mb-5">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <img src="<?php echo $product_details->image; ?>" class="img-fluid rounded" alt="<?php echo $product_details->name; ?>">
                        </div>
                        <div class="col-md-6">
                            <h1 class="card-title"><?php echo $product_details->name; ?></h1>
                            <p class="card-text">Mã sản phẩm: <?php echo $product_details->getId(); ?></p>
                            <?php if ($product_details->quantity > 0): ?>
                                <p class="card-text text-success">Tình trạng: Còn hàng</p>
                            <?php else: ?>
                                <p class="card-text text-danger">Tình trạng: Hết hàng</p>
                            <?php endif; ?>
                            <h3 class="card-text">Giá: <?php echo number_format($product_details->price, 0, ',', '.'); ?> VND</h3>
                            <!-- Thêm nút Đặt hàng hoặc thêm vào giỏ hàng nếu cần -->
                            <form action="/cart.php" method="post" class="mb-3">
                                <input type="hidden" name="product_image" value="<?php echo $product_details->image; ?>">
                                <input type="hidden" name="product_name" value="<?php echo $product_details->name; ?>">
                                <input type="hidden" name="product_price" value="<?php echo number_format($product_details->price, 0, ',', '.'); ?>">
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Số lượng:</label>
                                    <input type="number" id="quantity" name="product_number" class="form-control" value="1" min="1">
                                </div>
                                <button type="submit" class="btn btn-primary" name="add_cart">Đặt hàng</button>
                            </form>
                                <div>
                                <h2>Mô tả</h2>
                                <p class="card-text"><?php echo $product_details->description; ?></p>
                                </div>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
// Bao gồm footer và kết thúc trang HTML
include_once __DIR__ . '/../src/partials/footer.php';
?>
