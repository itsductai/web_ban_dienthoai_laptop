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

    h1{
        color: #d70018;
        font-weight: 800;
        text-align: center;
        background-image: linear-gradient(90deg, #ffd400, #ffff);
    }
    .des-text{
        font-size: 15px;
    }

    .item{
        border-radius: 50px;
        display: flex; justify-content: center;
    }
    .item :hover{
        transform: translateY(-5px);
        transition: 0.5s;
    }

</style>
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
    <h1 >Sản phẩm tìm kiếm :</h1>
            
                <?php if(count($products)!=0 && $search !='')
                {?>
                <p class="mini-info border border-info"><?= count($products)?> sản phẩm</p>
            <div class="row">
                <?php
                    foreach ($products as $row) {  
                        // Xác định đường dẫn của anh
                        $imagePath = isset($row->anh) ? html_escape($row->anh) : '';
                        $productName = isset($row->ten_san_pham) ? html_escape($row->ten_san_pham) : '';
                        $description = isset($row->mo_ta) ? html_escape($row->mo_ta) : '';
                        $price = isset($row->gia) ? $row->gia : 0;
                        $id_product = isset($row->san_pham_id) ? html_escape($row->san_pham_id) : '';
                        // Hiển thị mỗi sản phẩm trong một thẻ div.card
                        echo 
                        '<div class="col-lg-4 mb-4 item" style="">
                        <div class="card shadow" style="max-width: 16rem; border-radius:40px; text-align:center;">
                            <a href="/product_details.php?id=' . $id_product . '" class="card-link">
                                <img style="width: 80%;" src="' . $imagePath . '" class=" mt-2 card-img-top" alt="' . $productName . '">
                            </a>
                            <div class="card-body">
                                <h4 class="card-title " >' . $productName . '</h4>';
                                $arrs = explode("/",$description);
                                    foreach($arrs as $arr){
                                        echo '<li class="des-text" >' . $arr . '</li>';
                                    }
                                    echo '
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
                } else if($search=='') echo'<h4 class="pb-2 mb-4 text-danger border-bottom border-danger">Vui lòng nhập từ khóa cần tìm!</h4>';
                    else echo '<h4 class="pb-2 mb-4 text-danger border-bottom border-danger">Không tìm thấy sản phẩm nào!</h4>';
                 ?>
                 
            </div>
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
