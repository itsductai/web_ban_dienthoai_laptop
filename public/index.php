<?php
require_once __DIR__ . '/../src/bootstrap.php';

include_once __DIR__ . '/../src/partials/header.php';
// Kiểm tra xem có thông báo thành công không
if (isset($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']); // Xóa thông báo sau khi hiển thị
}
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
<style>
    .thumb1{
        display: flex;
        justify-content: center;
    }
    .thumb1 div{
        display: flex;
        border: 1px solid #d70018;
        margin: 15px;
        font-size: 20px;
        text-align: center;
        align-items: center;
        border-radius: 20px;
        color: white;
        background-image: linear-gradient(#d70018,#ffffff);
        box-shadow: 0px 0px 10px rgba(0, 0, 0,0.3);
    }
    .thumb1 img{
        width: 80px;
        padding: 5px;
        padding-right: 10px;
    }

    .thumb2{
        background-color: #ffd400;
    }

    .item{
        border-radius: 100px;
    }
</style>


<body>
<?php include_once __DIR__ . '/../src/partials/navbar.php' ?>


<div class="container">
    <div class="row">
            <div class="col-lg-12">
                <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                        <img src="Images/banner1.jpg" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                        <img src="Images/banner2.jpg" class="d-block w-100" alt="...">
                        </div>
                        <div class="carousel-item">
                        <img src="Images/banner3.jpg" class="d-block w-100" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="row thumb1">
                <div class="col-lg-3">
                        <img src="/Images/thumb1.jpg" alt=""> Laptop giá rẻ
                </div>

                <div class="col-lg-4">
                        <img src="/Images/thumb2.jpg" alt=""> Deal cực căng, hấp dẫn
                </div>

                <div class="col-lg-3">
                        <img src="/Images/thumb3.jpg" alt=""> Đổi 2G lên 4G
                </div>
            </div>
            
        </div>

        <div class="row thumb2">
            <img src="/Images/thumb_2.png" alt="">
            <div>
            <div class="row">
        
        <!-- danh mục sản phẩm -->
                    <!-- <div class="col-lg-3">
                        <h3>Danh mục</h3>
                        
                                <button class="btn btn-primary ">Điện thoại</button>
                            
                            
                                <button class="btn btn-primary">Laptop</button>
                            
                        
                        </div> -->
                    <div class="col-lg-12">
                        
                        <div class="row mt-4">
                            <?php foreach ($products as $row) {  
                                // Xác định đường dẫn của anh
                                $imagePath = isset($row->anh) ? html_escape($row->anh) : '';
                                $productName = isset($row->ten_san_pham) ? html_escape($row->ten_san_pham) : '';
                                $description = isset($row->mo_ta) ? html_escape($row->mo_ta) : '';
                                $price = isset($row->gia) ? $row->gia : 0;
                                $id_product = isset($row->san_pham_id) ? html_escape($row->san_pham_id) : '';
                                // Hiển thị mỗi sản phẩm trong một thẻ div.card
                                echo '
                                <div class="col-lg-4 mb-4 item" style="display: flex; justify-content: center;">
                                    <div class="card shadow" style="max-width: 16rem; border-radius:40px; text-align:center;">
                                        <a href="/product_details.php?id=' . $id_product . '" class="card-link">
                                            <img style="width: 80%;" src="' . $imagePath . '" class=" mt-2 card-img-top" alt="' . $productName . '">
                                        </a>
                                        <div class="card-body">
                                            <h4 class="card-title " >' . $productName . '</h4>
                                            
                                            <p class="card-text text-danger"><strong>' . number_format($price, 0, ',', '.') . ' VNĐ </strong></p>
                                            <form action="/cart.php" method="post">
                                                <input type="hidden" name="product_image" value="' . $imagePath . '">
                                                <input type="hidden" name="product_name" value="' . $productName . '">
                                                <input type="hidden" name="product_price" value="' . $price. '">
                                                <input type="hidden" name="product_number" id="product_number" value="1">
                                                <input type="hidden" name="product_id" value="' . $id_product . '">
                                                <button type="submit" class="btn" style=" background-color: #d70018; color: white;" name="add_cart" value="Đặt hàng"><i class="fa-solid fa-cart-shopping"></i> Mua ngay</button>  
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
            </div>
        </div>
    
<!-- Phân trang -->


<?php include_once __DIR__ . '/../src/partials/footer.php' ?>

</body>
</html>
