<?php
require_once __DIR__ . '/../src/bootstrap.php';

include_once __DIR__ . '/../src/partials/header.php';
?>
<style>
    .mini-info{
	font-size: 12px;
	border-radius: 5px;
	width: 100px;
	height: 20px;
    text-align: center;
}
.des-text{
        font-size: 15px;
    }
</style>
<?php
    use CT275\Project\Product;
    $hang =$_GET['hang'] ?? '';
    $gia =$_GET['gia'] ?? '';
    $product = new Product($PDO);
    $products = $product->filter_laptop($hang,$gia);
?>
<body>
<?php include_once __DIR__ . '/../src/partials/navbar.php' ?>


<div class="container mt-5 mb-5">
    <div class="row">
        <form action="" method="GET">
            <div class="row">
                <div class="col-md-6">
                    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php">Trang chủ</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
                        </ol>
                    </nav>
                </div>
            
                <div class="col-md-2 mt-1" >
                    <select name="hang" id="" class="border-warning form-select">
                        <option value="">Hãng</option>

                        <option value="macbook" 
                            <?= isset($_GET['hang'])==true ? ($_GET['hang'] =='macbook' ? 'selected':''):'' ?> 
                        >
                            Macbook
                        </option>
                        <option value="hp" 
                            <?= isset($_GET['hang'])==true ? ($_GET['hang'] =='hp' ? 'selected':''):'' ?>
                        >
                            HP
                        </option>
                        <option value="lenovo"
                            <?= isset($_GET['hang'])==true ? ($_GET['hang'] =='lenovo' ? 'selected':''):'' ?>
                        >
                            Lenovo
                        </option>
                    </select>
                </div>

                <div class="col-md-2 mt-1">
                    <select name="gia" id="" class="border-warning form-select pr-2">
                        <option value="">Giá</option>
                        <option value="thap_cao" <?= isset($_GET['gia'])==true ? ($_GET['gia'] =='thap_cao' ? 'selected':''):'' ?>>Từ thấp đến cao</option>
                        <option value="cao_thap" <?= isset($_GET['gia'])==true ? ($_GET['gia'] =='cao_thap' ? 'selected':''):'' ?>>Từ cao đến thấp</option>
                    </select>
                </div>

                <div class="col-md-2 mt-1">
                    <button type="submit" class="btn" style="background-color: #d70018; color: #ffff;">Lọc</button>
                    <a href="phone.php" class="btn btn-warning">Bỏ chọn</a>
                </div>
            </div>
        </form>
        
        <div class="col-lg-12">
        
            

            <h1 >Laptop :</h1>
            <p class="mini-info border border-info"><?= count($products)?> Laptop</p>
            <div class="row">
                <?php if(count($products)!=0)
                {
                    foreach ($products as $row) {  
                        // Xác định đường dẫn của anh
                        $imagePath = isset($row->anh) ? html_escape($row->anh) : '';
                        $productName = isset($row->ten_san_pham) ? html_escape($row->ten_san_pham) : '';
                        $description = isset($row->mo_ta) ? html_escape($row->mo_ta) : '';
                        $price = isset($row->gia) ? $row->gia : 0;
                        $id_product = isset($row->san_pham_id) ? html_escape($row->san_pham_id) : '';
                        // Hiển thị mỗi sản phẩm trong một thẻ div.card
                        echo '
                        <div class="col-lg-4 mb-5">
                            <div class="card shadow" style="width: 18rem;">
                                <img src="' . $imagePath . '" class="card-img-top" alt="' . $productName . '">
                                <div class="card-body">
                                    <h5 class="card-title">' . $productName . '</h5>';
                                    $arrs = explode("/",$description);
                                    foreach($arrs as $arr){
                                        echo '<li class="des-text">' . $arr . '</li>';
                                    }
                                    echo '
                                    <p class="card-text">Giá: ' . number_format($price, 0, ',', '.') . '</p>
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
                } else echo'<h4 class="pb-2 mb-4 text-danger border-bottom border-danger">Không tìm thấy sản phẩm nào!</h4>';
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
