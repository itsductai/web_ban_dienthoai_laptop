
<?php
session_start();


// Kiểm tra nếu giỏ hàng chưa được khởi tạo, khởi tạo giỏ hàng là một mảng trống
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}
// Xóa tất cả đơn hàng trong giỏ hàng
if (isset($_GET['delcart']) && ($_GET['delcart']==1)){unset($_SESSION['cart']);} 
 //  Xóa 1 sản phẩm trong giở hàng   
if (isset($_GET['delid']) && ($_GET['delid']>=0)) {
    $delid = intval($_GET['delid']);
    if (isset($_SESSION['cart'][$delid])) {
        unset($_SESSION['cart'][$delid]);
    }
}
// Xử lý khi người dùng thêm sản phẩm vào giỏ hàng
if (isset($_POST['add_cart']) && ($_POST['add_cart'])) {
   
        // Lấy thông tin sản phẩm từ form
        $product_image = $_POST['product_image'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_number = $_POST['product_number'];
        $product_id = $_POST['product_id'];

        // Kiểm tra sản phẩm cho trong giỏ hàng hay chưa
        $exist = false;
        foreach ($_SESSION['cart'] as $index => $item) {
            if ($item[1] === $product_name) {
                $exist = true;
                // Cập nhật số lượng sản phẩm
                $_SESSION['cart'][$index][3] += $product_number;
                break;
            }
        }
    // Nếu sản phẩm chưa có trong giỏ hàng, thêm mới vào
        if (!$exist) {
            $product = [$product_image, $product_name, $product_price, $product_number,$product_id ];
            $_SESSION['cart'][] = $product;
        }  
    }   
        function showCart()
        {
            if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])&& count($_SESSION['cart']) > 0  ) {
            $total = 0;
            $subtotal = 0;
            foreach ($_SESSION['cart'] as $index => $product) {
                $subtotal = floatval($product[2]) * intval($product[3]); // Tính tổng giá của mỗi sản phẩm
                $total += $subtotal; // Cập nhật tổng giá trị
                echo '
                <tr class="text-center">
                    <td class="align-middle">' . ($index + 1) . '</td>
                    <td class="align-middle">' . $product[1] . '</td>
                    <td class="align-middle"><img src="/Images/' . $product[0] . '" alt="' . $product[1] . '" class="product-image" style="width: 100px;"></td>
                    <td class="align-middle">' . $product[3] . '</td>
                    <td class="align-middle">' .number_format($subtotal, 0, ',', '.'). '</td>
                    <td class="align-middle">
                    <a href="cart.php?delid='.$index.'"class="btn btn-danger" onclick="return confirmDelete()">Xóa</a></td>
                    </tr>';
            }
            echo'<tr class="text-center">
            <th colspan="4" class="align-middle">Tổng giá</th>
            <th colspan="2" class="align-middle">' . number_format($total, 0, ',', '.') . ' VNĐ</th>
            </tr>';
        }else {
            echo '<tr><td colspan="6" class="text-center">Giỏ hàng của bạn đang trống</td></tr>';
        }
    }
        
?>
<?php
require_once __DIR__ . '/../src/bootstrap.php';

include_once __DIR__ . '/../src/partials/header.php';
?>
<body>
<?php include_once __DIR__ . '/../src/partials/navbar.php' ?>
    <div class="container mt-5 border">
        <h1 class="mb-4">Giỏ hàng</h1>
        <div class="mb-3">
            <a href="cart.php?delcart=1" class="btn btn-danger mb-3" onclick="return confirmAllDelete()">
                <i class="fas fa-trash-alt me-1"></i> Xóa giỏ hàng
            </a>
            <a href="index.php?" class="btn btn-warning mb-3">
                <i class="fa-solid fa-cart-shopping"></i> Đặt hàng
            </a>
            <a href="checkout.php" class="btn btn-success mb-3">
                <i class="fas fa-money-bill-alt"></i> Thanh toán
            </a>
        </div>
           
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr class="text-center">
                        <th scope="col"  class="align-middle">STT</th>
                        <th scope="col"  class="align-middle">Tên sản phẩm</th>
                        <th scope="col"  class="align-middle">Ảnh</th>
                        <th scope="col"  class="align-middle">Số lượng</th>
                        <th scope="col"  class="align-middle">Giá</th>
                        <th scope="col"  class="align-middle">Xóa</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <!-- in các sản phẩm ra mà hình -->
                   <?php showcart(); ?>
                    
                </tbody>
            </table>
        </div>
    </div>
    <?php include_once __DIR__ . '/../src/partials/footer.php' ?>
    <script>
        function confirmAllDelete() {
            return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng hay không?');
        }
        function confirmDelete() {
            return confirm('Bạn có chắc chắn muốn xóa sản phẩm này ra khỏi giỏ hàng không?');
        }
    </script>
</body>

</html>
