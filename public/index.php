<?php
require_once __DIR__ . '/../src/bootstrap.php';

include_once __DIR__ . '/../src/partials/header.php';
?>
<?php
use CT275\Project\Product;
$product = new Product($PDO);
$products = $product->all();
use CT275\Project\Paginator;
$limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? (int)$_GET['limit'] : 9;
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;
$paginator = new Paginator(
    recordsPerPage: $limit,
    totalRecords: $product->count(),
    currentPage: $page
);
$products = $product->paginate($paginator->recordOffset, $paginator->recordsPerPage);
$pages = $paginator->getPages(length: 3);

?>
<body>
<?php include_once __DIR__ . '/../src/partials/navbar.php' ?>


<div class="container mt-5 mb-5">
    <div class="row">
        <!-- danh mục sản phẩm -->
        <div class="col-lg-3">
            <h3>Danh mục</h3>
            
                    <button class="btn btn-primary ">Điện thoại</button>
                
                
                    <button class="btn btn-primary">Laptop</button>
                
            
        </div>
        <div class="col-lg-9">
            <h1>Sản phẩm</h1>
            <div class="row">
                <?php foreach ($products as $row) {  
                    // Xác định đường dẫn của anh
                    $imagePath = isset($row->anh) ? 'Images/' . $row->anh : '';
                    // Lấy các thông tin sản phẩm gom ten_san_pham, mo_ta, gia
                    $productName = isset($row->ten_san_pham) ? html_escape($row->ten_san_pham) : '';
                    $description = isset($row->mo_ta) ? html_escape($row->mo_ta) : '';
                    $price = isset($row->gia) ? $row->gia : 0;

                    // Hiển thị mỗi sản phẩm trong một thẻ div.card
                    echo '
                    <div class="col-lg-4 px- mb-2">
                        <div class="card" style="width: 18rem;">
                            <img src="' . $imagePath . '" class="card-img-top" alt="' . $productName . '">
                            <div class="card-body">
                                <h5 class="card-title">' . $productName . '</h5>
                                <p class="card-text">' . $description . '</p>
                                <p class="card-text">Giá: ' . number_format($price, 0, ',', '.') . '</p>
                                <a href="#" class="btn btn-primary">Mua hàng <i class="fa-solid fa-cart-shopping"></i></a>
                            </div>
                        </div>
                    </div>';
}
?>
            </div>
        </div>

    </div>
</div>
<!-- Phân trang -->
<nav class="d-flex justify-content-center">
    <ul class="pagination">
        <li class="page-item<?= $paginator->getPrevPage() === false ? ' disabled' : '' ?>">
            <a role="button" href="/?page=<?= $paginator->getPrevPage() ?>&limit=<?= $limit ?>" class="page-link">
                <span>&laquo;</span>
            </a>
        </li>
        <?php foreach ($pages as $page) : ?>
            <li class="page-item<?= $paginator->currentPage === $page ? ' active' : '' ?>">
                <a role="button" href="/?page=<?= $page ?>&limit=<?= $limit ?>" class="page-link"><?= $page ?></a>
            </li>
        <?php endforeach ?>
        <li class="page-item<?= $paginator->getNextPage() === false ? ' disabled' : '' ?>">
            <a role="button" href="/?page=<?= $paginator->getNextPage() ?>&limit=<?= $limit ?>" class="page-link">
                <span>&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

<?php include_once __DIR__ . '/../src/partials/footer.php' ?>
<script>
    // Lấy tham chiếu đến nút "Login"
    var loginButton = document.getElementById("loginButton");

    // Thêm sự kiện click cho nút "Login"
    loginButton.addEventListener("click", function() {
        // Chuyển hướng sang trang đăng nhập
        window.location.href = "login.php"; // Thay 'login.html' bằng đường dẫn thích hợp của trang đăng nhập
    });
</script>
</body>
</html>
