<header class="p-3 bg-danger text-white">
        <div class="container">
            <div class="d-flex flex-wrap align-items-center justify-content-center justify-content-lg-start">
            <a href="/" class="d-flex align-items-center mb-2 mb-lg-0 text-white text-decoration-none">
                    <img src="Images/logo.jpg" alt="Logo" width="70" height="50" class="me-2">
                </a>
                <!-- hiển thị thanh navbar trang khi đăng nhập với tài khoản admin-->
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <?php if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin') : ?>
                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                        <li><a href="index.php" class="nav-link px-2 text-white">Trang chủ</a></li>
                        <li><a href="Admin.php" class="nav-link px-2 text-white">Sản Phẩm</a></li>
                        <li><a href="category.php" class="nav-link px-2 text-white">Danh mục</a></li>
                        <li><a href="order.php" class="nav-link px-2 text-white">Đơn hàng</a></li>
                    </ul>
                    <a href="cart.php"class="btn  btn-outline-light "><i class="fa-solid fa-cart-shopping"></i></a>  
                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                    <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
                </form>
                <div class="text-end">
               
                    <?php
                // Kiểm tra xem người dùng đã đăng nhập hay chưa
                if (isset($_SESSION['user_id'])) {
                    // Nếu đã đăng nhập, hiển thị nút "Logout" và tên người dùng
                    echo '<div class="d-flex align-items-center">';
                    echo '<a href="logout.php" onclick="return confirmLogout()" class="btn btn-warning ml-auto">Logout</a>';
                    echo '<span class="text-white mr-2 ml-3 border border-light rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">';
                    echo '<i class="fa-solid fa-user"></i>';
                    echo '</span>';  
                    echo '<span class="text-white">' . $_SESSION['username'] . '</span>'; // Tên người dùng
                    echo '</div>';
                    
                }
                ?>

                </div>
                 <!-- hiển thị thanh navbar trang khi đăng nhập với tài khoản người dùng -->
                <?php else : ?>
                    <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                        <li><a href="index.php" class="nav-link px-2 text-white">Trang chủ</a></li>
                        <li><a href="#" class="nav-link px-2 text-white">Sản Phẩm</a></li>
                        <li><a href="#" class="nav-link px-2 text-white">Liên hệ</a></li>
                    </ul>
                    <a href="cart.php"class="btn  btn-outline-light "><i class="fa-solid fa-cart-shopping"></i></a>  
                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                    <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
                </form>
                <div class="text-end">
               
                
                    <?php
                // Kiểm tra xem người dùng đã đăng nhập hay chưa
                if (isset($_SESSION['user_id'])) {
                    // Nếu đã đăng nhập, hiển thị nút "Logout" và tên người dùng
                    echo '<div class="d-flex align-items-center">';
                    echo '<a href="logout.php" onclick="return confirmLogout()" class="btn btn-warning ml-auto">Logout</a>';
                    echo '<span class="text-white mr-2 ml-3 border border-light rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">';
                    echo '<i class="fa-solid fa-user"></i>';
                    echo '</span>';  
                    echo '<span class="text-white">' . $_SESSION['username'] . '</span>'; // Tên người dùng
                    echo '</div>';
                }
                ?>
                </div>
                <?php endif; ?>
                <!-- hiển thị thanh navbar trang khi chưa đăng nhập -->
            <?php else : ?>
                <ul class="nav col-12 col-lg-auto me-lg-auto mb-2 justify-content-center mb-md-0">
                    <li><a href="index.php" class="nav-link px-2 text-white">Trang chủ</a></li>
                    <li><a href="#" class="nav-link px-2 text-white">Sản Phẩm</a></li>
                    <li><a href="contac.php" class="nav-link px-2 text-white">Liên hệ</a></li>
                </ul>
                <a href="cart.php"class="btn  btn-outline-light "><i class="fa-solid fa-cart-shopping"></i></a>  
                <form class="col-12 col-lg-auto mb-3 mb-lg-0 me-lg-3">
                    <input type="search" class="form-control form-control-dark" placeholder="Search..." aria-label="Search">
                </form>

                <div class="text-end">
                    <a href="signup.php"class="btn  btn-outline-light me-2">Signup</a>
                    <a href="login.php" class="btn btn-warning me-2">Login</a>
                </div>
                
            <?php endif; ?>
                
            </div>
        </div>  
    </header>