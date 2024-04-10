<?php
require_once __DIR__ . '/../src/bootstrap.php';

include_once __DIR__ . '/../src/partials/header.php';
?>
<?php
use CT275\Project\Product;
$search =$_GET['search'] ?? '';
$product = new Product($PDO);
$products = $product->search($search);
?>
<body>
<?php include_once __DIR__ . '/../src/partials/navbar.php' ?>


<div class="container mt-5 mb-5">
    <div class="row">
        <div class="col-lg-9">
    
        
            <h1>Sản phẩm tìm thấy:</h1>
            <div id="search-results" class="row">
                <?php
                if(count($products)!=0 && $search!='') {
                    foreach ($products as $row) {  
                        // Xác định đường dẫn của anh
                        $imagePath = isset($row->anh) ? html_escape($row->anh) : '';
                        $productName = isset($row->ten_san_pham) ? html_escape($row->ten_san_pham) : '';
                        $description = isset($row->mo_ta) ? html_escape($row->mo_ta) : '';
                        $price = isset($row->gia) ? $row->gia : 0;
                        
                        // Hiển thị mỗi sản phẩm trong một thẻ div.card
                        echo '
                        <div class="col-lg-4 px- mb-2">
                            <div class="card" style="width: 18rem;">
                                <img src="/Images/' . $imagePath . '" class="card-img-top" alt="' . $productName . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . $productName . '</h5>
                                    <p class="card-text">' . $description . '</p>
                                    <p class="card-text">Giá: ' . number_format($price, 0, ',', '.') . '</p>
                                    <form action="/cart.php" method="post">
                                        
                                        <input type="hidden" name="product_image" value="' . $imagePath . '">
                                        <input type="hidden" name="product_name" value="' . $productName . '">
                                        <input type="hidden" name="product_price" value="' . number_format($price, 0, ',', '.') . '">
                                        <input type="submit" class="btn btn-primary" name="add_cart" value="đặt hàng">  
                                        <input type="number" class="btn btn-outline-dark btn-sm" name="product_number" id="product_number" value="1" min="1" max="10">
                                        
                                    </form>
                                </div>
                            </div>  
                        </div>';
                        
                    } 
                } else {
                    echo '<p> Không tìm thấy sản phẩm nào.</p>';
                }
                
                ?>
            </div>
        </div>

    </div>
</div>


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
