<?php
require_once __DIR__ . '/../src/bootstrap.php';

include_once __DIR__ . '/../src/partials/header.php';
?>
<?php
use CT275\Project\Product;
use CT275\Project\Paginator;
$product = new Product($PDO);

$limit = (isset($_GET['limit']) && is_numeric($_GET['limit'])) ? (int)$_GET['limit'] : 5;
$page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;
$paginator = new Paginator(
    totalRecords: $product->count(),
    recordsPerPage: $limit,
    currentPage: $page
);
$products = $product->paginate($paginator->recordOffset, $paginator->recordsPerPage);
$pages = $paginator->getPages(length: 3);
?>

<body>
<?php include_once __DIR__ . '/../src/partials/navbar.php' ?>
  
    <div class="container">
        <div class="row mt-3">
            <div class="col-12">
                    <a href="/add.php" class="btn btn-primary mb-3">
                        <i class="fa fa-plus"></i> Thêm sản phẩm
                    </a>
                    
                <table class="table table-striped table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th scope="col"  style="width: 20%">Tên sản phẩm</th>
                                    <th scope="col" style="width: 20%">Mô tả</th>
                                    <th scope="col" style="width: 10%">Giá</th>
                                    <th scope="col"style="width: 5%">Số lượng</th>
                                    <th scope="col"style="width: 15%">Hình ảnh</th>
                                    <th scope="col"  style="width: 5%">Danh mục</th>
                                    <th scope="col"style="width: 30%">Thao tác</th>
                                </tr>
                                </thead>
                                <tbody>
                            
                        <?php foreach($products as $product):?>
                            
                            <tr>
                                <td class="align-middle"><?=html_escape($product->ten_san_pham)?></td>
                                <td class="align-middle"><?=html_escape($product->mo_ta)?></td>
                                <td class="align-middle"><?=html_escape($product->gia)?></td>
                                <td class="align-middle"><?=html_escape($product->so_luong)?></td>
                                <td class="align-middle">
                                    <?php if (!empty($product->anh)): ?>
                                        <img src="<?=html_escape($product->anh)?>" style="width: 100px" alt="Hình ảnh sản phẩm" class="ProductImage" >
                                    <?php else: ?>
                                        <span>Chưa có ảnh</span>
                                    <?php endif; ?>
                                </td>
                                <td class="align-middle"><?=html_escape($product->danh_muc_id)?></td>
                            
                                
                                <td class="align-middle">
                                    <div class="button-container">
                                        <a href="<?='edit.php?id=' .$product->san_pham_id?>" class="btn btn-xs btn-warning mr-2">
                                            <i alt="Edit" class="fa fa-pencil"></i> Edit</a>
                                        <form class="form-inline mb-2" action="/delete.php" method="POST">
                                            <input type="hidden" name="id" value="<?= $product->san_pham_id ?>">
                                            <button type="submit" class="btn btn-xs btn-danger" name="delete-product">
                                                <i alt="Delete" class="fa fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>


                                
                                
                            </tr>
                        <?php endforeach ?>
                    
                            </tbody>                    
                            
                    </table>
                            
                </div>
        </div>
    </div>

<!-- Phân trang -->
<nav class="d-flex justify-content-center">
    <ul class="pagination">
        <li class="page-item<?= $paginator->getPrevPage() ?'' : ' disabled' ?>">
            <a role="button" href="/Admin.php?page=<?= $paginator->getPrevPage() ?>&limit=5" class="page-link">
                <span>&laquo;</span>
            </a>
        </li>
    <?php foreach ($pages as $page) : ?>
        <li class="page-item<?= $paginator->currentPage === $page ?' active' : '' ?>">
            <a role="button" href="/Admin.php?page=<?= $page ?>&limit=5" class="page-link"><?= $page ?></a>
        </li>
    <?php endforeach ?>
        <li class="page-item<?= $paginator->getNextPage() ?'' : ' disabled' ?>">
            <a role="button" href="/Admin.php?page=<?= $paginator->getNextPage() ?>&limit=5" class="page-link">
                <span>&raquo;</span>
            </a>
        </li>
    </ul>
</nav>

    <div id="delete-confirm" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">Bạn có muốn xóa sản phẩm này không?</div>
                <div class="modal-footer">
                    <button type="button" data-dismiss="modal" class="btn btn-danger" id="delete">Delete</button>
                    <button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<?php include_once __DIR__ . '/../src/partials/footer.php' ?>
<script>
        $(document).ready(function(){
            $('button[name="delete-product"]').on('click', function(e){
                e.preventDefault();

                const form = $(this).closest('form');
                const nameTd = $(this).closest('tr').find('td:first');

                if (nameTd.length > 0) {
                    $('.modal-body').html(
                    `Bạn có muốn xóa sản phẩm "${nameTd.text()} hay không"?`
                    );
                }

                $('#delete-confirm').modal({
                    backdrop: 'static', keyboard: false
                }) 
                .on('click', '#delete', function() {
                    form.trigger('submit');
                });
            });
        });
    
    </script>
</body>
</html>
