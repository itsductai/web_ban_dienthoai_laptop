<?php
    require_once __DIR__ . '/../src/bootstrap.php';
    // Phần captcha
    require __DIR__ . '/../vendor/autoload.php';
    use Gregwar\Captcha\CaptchaBuilder;
    use Gregwar\Captcha\PhraseBuilder;
    
    $builder = new CaptchaBuilder;
    $builder->build();

    

    
    //
    define('TITLE', 'Đăng nhập');
    include_once __DIR__ . '/../src/partials/header.php';
    include_once __DIR__ . '/../src/partials/navbar.php';

    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if(isset($_SESSION['phrase']) && 
            PhraseBuilder::comparePhrases($_SESSION['phrase'],$_POST['captcha'])
        ){
            if (!empty($_POST['username']) && !empty($_POST['password'])) {
                require_once __DIR__ . '/../src/db_connect.php';
    
                $query = "SELECT * FROM thanh_vien WHERE username = ?";
                try {
                    $statement = $pdo->prepare($query);
                    $statement->execute([$_POST['username']]);
                    $user = $statement->fetch(PDO::FETCH_ASSOC);
                    if ($_POST['username'] === 'admin' && $_POST['password'] === 'admin123') {
                        // Đăng nhập thành công cho tài khoản admin
                        $_SESSION['user_id'] = $user['thanh_vien_id'];
                        $_SESSION['username'] = $_POST['username']; // Lưu tên người dùng vào phiên
                        echo '<script>alert("Đăng nhập thành công!");</script>';
                        // Chuyển hướng người dùng sau khi đăng nhập thành công
                        echo '<script>window.location.href = "/Admin.php";</script>';
                        exit;
                    } elseif ($_POST['password'] === $user['password']) {
                        // Đăng nhập thành công cho tài khoản thường
                        $_SESSION['user_id'] = $user['thanh_vien_id'];
                        $_SESSION['username'] = $_POST['username']; // Lưu tên người dùng vào phiên
                        echo '<script>alert("Đăng nhập thành công!");</script>';
                        // Chuyển hướng người dùng sau khi đăng nhập thành công
                        echo '<script>window.location.href = "/index.php";</script>';
                        exit;
                    } else {
                        $error_message = 'Tên người dùng hoặc mật khẩu không đúng!';
                    }
                } catch (PDOException $e) {
                    $pdo_error = $e->getMessage();
                    $error_message = 'Không thể thực hiện đăng nhập vì ' . ($pdo_error ?? 'lỗi không xác định');
                }
            } else {
                $error_message = 'Vui lòng điền đầy đủ tên người dùng và mật khẩu!';
            }
        } else {
            echo '<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Mã captcha sai!</strong> Vui lòng nhập lại.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>';
        }
        
    }

    
    $_SESSION['phrase'] = $builder->getPhrase();
    ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5 mb-5">
                    <div class="card-body">
                        <h2 class="card-title mb-3 text-center font-weight-bold">ĐĂNG NHẬP</h2>
                        <?php if (isset($error_message)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $error_message ?>
                            </div>
                        <?php endif; ?>
                        <form action="login.php" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên người dùng</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="mb-3">
                                <label for="captcha" class="form-label mr-2">Mã captcha </label>
                                <img src="<?= $builder->inline() ?>" alt="">
                                <input type="text" class="form-control mt-3" id="captcha" name="captcha" required>
                            </div>
                            <input type="submit" name="submit" class="btn text-white" style="background-color: #d70018;" value="Đăng nhập">
                            <a href="#" class="btn btn-warning mx-2">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    include_once __DIR__ . '/../src/partials/footer.php';
    ?>
