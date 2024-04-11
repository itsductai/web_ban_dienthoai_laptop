<?php
require_once __DIR__ . '/../src/bootstrap.php';

include_once __DIR__ . '/../src/partials/header.php';
?>
<?php
use CT275\Project\Product;
use CT275\Project\Paginator;
$product = new Product($PDO);

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
                    $imagePath = isset($row->anh) ? html_escape($row->anh) : '';
                    $productName = isset($row->ten_san_pham) ? html_escape($row->ten_san_pham) : '';
                    $description = isset($row->mo_ta) ? html_escape($row->mo_ta) : '';
                    $price = isset($row->gia) ? str_replace(['.', ','], ['', ''], $row->gia) : 0;
                    $id_product = isset($row->san_pham_id) ? html_escape($row->san_pham_id) : '';
                    // Hiển thị mỗi sản phẩm trong một thẻ div.card
                    echo '
                    <div class="col-lg-4 mb-2">
                        <div class="card" style="width: 18rem;">
                            <img src="' . $imagePath . '" class="card-img-top" alt="' . $productName . '">
                            <div class="card-body">
                                <h2 class="card-title">' . $productName . '</h2>
                                <p class="card-text">' . $description . '</p>
                                <p class="card-text">Giá: ' . number_format($price, 0, ',', '.') . ' VNĐ</p>
                                <form action="/cart.php" method="post">
                                    <input type="hidden" name="product_image" value="' . $imagePath . '">
                                    <input type="hidden" name="product_name" value="' . $productName . '">
                                    <input type="hidden" name="product_price" value="' . $price. '">
                                    <input type="hidden" name="product_number" id="product_number" value="1">
                                    <input type="hidden" name="product_id" value="' . $id_product . '">
                                    <input type="submit" class="btn btn-primary" name="add_cart" value="đặt hàng">  
                                </form>
                            </div>
                        </div>  
                    </div>';
                    
                    }
                ?>
        
            </div>
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
        </div>

    </div>
</div>
<!-- Phân trang -->


<?php include_once __DIR__ . '/../src/partials/footer.php' ?>

</body>
</html>
