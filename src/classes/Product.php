<?php

namespace CT275\project;

use PDO;

class Product
{
    private ?PDO $db;

    private int $id = -1;
    public $name;
    public $description;
    public $price;
    public $quantity;
    public $image;
    public $category_id;
    private array $errors = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function __construct(?PDO $pdo)
    {
        $this->db = $pdo;
    }

    public function fill(array $data): Product
    {
        $this->name = $data['ten_san_pham'] ?? '';
        $this->description = $data['mo_ta'] ?? '';
        $this->price = $data['gia'] ?? '';
        $this->quantity = $data['so_luong'] ?? '';
        $this->image = $data['anh'] ?? '';
        $this->category_id = $data['danh_muc_id'] ?? '';
        
        return $this;
    }
    
    public function getValidationErrors(): array
    {
        return $this->errors;
    }

    public function validate(): bool
    {
    $errors = [];

    // Xác thực tên sản phẩm
    $name = trim($this->name);
    if (empty($name)) {
        $errors['ten_san_pham'] = 'Vui lòng nhập tên sản phẩm.';
    }

    // Xác thực mô tả
    $description = trim($this->description);
    if (empty($description)) {
        $errors['mo_ta'] = 'Vui lòng nhập mô tả sản phẩm.';
    }

    // Xác thực giá
    $price = trim($this->price);
    if (!is_numeric($price) || $price <= 0) {
        $errors['gia'] = 'Vui lòng nhập giá sản phẩm hợp lệ.';
    }

    // Xác thực số lượng
    $quantity = trim($this->quantity);
    if (!is_numeric($quantity) || $quantity <= 0) {
        $errors['so_luong'] = 'Vui lòng nhập số lượng sản phẩm hợp lệ.';
    }

    // Xác thực hình ảnh
    if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $errors['image'] = 'Vui lòng chọn hình ảnh sản phẩm.';
    } else {
        $imageFileType = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if ($imageFileType !== 'jpg' && $imageFileType !== 'png' && $imageFileType !== 'jpeg' && $imageFileType !== 'gif') {
            $errors['image'] = 'Chỉ chấp nhận các file hình ảnh có định dạng JPG, JPEG, PNG hoặc GIF.';
        }
    }

    // Xác thực danh mục
    $categoryId = trim($this->category_id);
    if (empty($categoryId)) {
        $errors['danh_muc_id'] = 'Vui lòng chọn danh mục sản phẩm.';
    }

    // Lưu các lỗi vào đối tượng sản phẩm
    $this->errors = $errors;

    // Trả về true nếu không có lỗi, ngược lại trả về false
    return empty($errors);
    }

    protected function fillFromDB(array $row): Product
    {
        $this->id = $row['san_pham_id'] ?? -1;
        $this->name = $row['ten_san_pham'] ?? '';
        $this->description = $row['mo_ta'] ?? '';
        $this->price = $row['gia'] ?? '';
        $this->quantity = $row['so_luong'] ?? '';
        $this->image = $row['anh'] ?? '';
        $this->category_id = $row['danh_muc_id'] ?? '';
        
        return $this;
    }
    public function all(): array
    {
        $products = [];
        $statement = $this->db->prepare('SELECT * FROM san_pham');
        $statement->execute();
        while ($row = $statement->fetch()) {
            $product = new Product($this->db);
            $product->fill($row);
            $products[] = $product;
        }
        return $products;
    }

    public function save(): bool
{
    $result = false;
    if ($this->id >= 0) {
        $statement = $this->db->prepare('UPDATE san_pham SET ten_san_pham = :name, mo_ta = :description, gia = :price, so_luong = :quantity, anh = :image, danh_muc_id = :category_id WHERE san_pham_id = :id');
        $result = $statement->execute([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'image' => $this->image,
            'category_id' => $this->category_id,
            'id' => $this->id
        ]);
    } else {
        $statement = $this->db->prepare('INSERT INTO san_pham (ten_san_pham, mo_ta, gia, so_luong, anh, danh_muc_id) VALUES (:name, :description, :price, :quantity, :image, :category_id)');
        $result = $statement->execute([
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'image' => $this->image,
            'category_id' => $this->category_id
        ]);
        if ($result) {
            $this->id = $this->db->lastInsertId();
        }
    }
    return $result;
}


    public function delete(): bool
    {
        $statement = $this->db->prepare('DELETE FROM san_pham WHERE san_pham_id = :id');
        return $statement->execute(['id' => $this->id]);
    }
    public function find(int $id): ?Product
    {
        $statement = $this->db->prepare('SELECT * FROM san_pham WHERE san_pham_id = :id');
        $statement->execute(['id' => $id]);
        if ($row = $statement->fetch()) {
            $this->fillFromDB($row);
            return $this;
        }
        return null;
    }
    
    public function count(): int
    {
        $statement = $this->db->prepare('SELECT COUNT(*) FROM san_pham');
        $statement->execute();
        return $statement->fetchColumn();
    }
    public function paginate(int $offset, int $limit): array
    {
        $statement = $this->db->prepare('SELECT * FROM san_pham LIMIT :offset, :limit');
        $statement->bindValue(':offset', $offset, PDO::PARAM_INT);
        $statement->bindValue(':limit', $limit, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_OBJ);
    }
    public function getAllCategories(): array
    {
        $categories = [];
        $statement = $this->db->prepare('SELECT * FROM danh_muc');
        $statement->execute();
        while ($row = $statement->fetch()) {
            $categories[] = $row;
        }
        return $categories;
    }
    public function update(array $data): bool
    {
        $oldAvatarPath = $this->getImagePath();

    $this->fill($data);
    if ($this->validate()) {
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
            if ($oldAvatarPath && file_exists($oldAvatarPath)) {
                unlink($oldAvatarPath);
            }
            $uploadDir = 'Images/';
            $imagePath = $uploadDir . basename($_FILES['image']['name']);
            move_uploaded_file($_FILES['image']['tmp_name'], $imagePath);
            $this->setImage($imagePath);
        }elseif ($oldAvatarPath) {
            unlink($oldAvatarPath);
        }
        return $this->save();
    }
    return false;
    }
    public function getImagePath(): ?string
    {
    return $this->image;
    }
    public function setImage(string $imagePath): void
    {
    $this->image = $imagePath;
    }

    public function search(string $search): array
    {
        
            $statement = $this->db->prepare('SELECT * FROM san_pham WHERE ten_san_pham LIKE :search');
            $statement->execute([
                ':search' => "%$search%"
            ]);
            return $statement->fetchAll(PDO::FETCH_OBJ);
       
    }

    public function filter_dt(string $hang,string $gia): array
    {   
        if($hang=='' && $gia=='') {
            $statement = $this->db->prepare('SELECT * FROM san_pham WHERE danh_muc_id= 1');
            $statement->execute();
            return $statement->fetchAll(PDO::FETCH_OBJ);
        }else if(isset($_GET['hang']) && $_GET['hang']!=''&& isset($_GET['gia']) && $_GET['gia']=='thap_cao' && $_GET['gia'] !='')
            {
                $statement = $this->db->prepare('SELECT * FROM san_pham WHERE (ten_san_pham LIKE :hang AND danh_muc_id=1)  ORDER BY gia');
                $statement->execute([
                    ':hang' => "%$hang%"
                ]);
                return $statement->fetchAll(PDO::FETCH_OBJ);
            } else if(isset($_GET['hang']) && $_GET['hang']!=''&& isset($_GET['gia']) && $_GET['gia']=='cao_thap' && $_GET['gia'] !='') 
                {
                    $statement = $this->db->prepare('SELECT * FROM san_pham WHERE (ten_san_pham LIKE :hang AND danh_muc_id=1) ORDER BY gia DESC');
                    $statement->execute([
                        ':hang' => "%$hang%"
                    ]);
                    return $statement->fetchAll(PDO::FETCH_OBJ);
                } 
                    else if(isset($_GET['hang']) && $_GET['hang']!=''&& $_GET['gia'] =='')
                    {
                        $statement = $this->db->prepare('SELECT * FROM san_pham WHERE (ten_san_pham LIKE :hang AND danh_muc_id=1)');
                        $statement->execute([
                            ':hang' => "%$hang%"
                        ]);
                        return $statement->fetchAll(PDO::FETCH_OBJ);
                    } else if(isset($_GET['hang']) && $_GET['hang']==''&& isset($_GET['gia']) && $_GET['gia']=='cao_thap' && $_GET['gia'] !='')
                        {
                            $statement = $this->db->prepare('SELECT * FROM san_pham WHERE danh_muc_id= 1 ORDER BY gia DESC');
                            $statement->execute();
                            return $statement->fetchAll(PDO::FETCH_OBJ);
                        } else if(isset($_GET['hang']) && $_GET['hang']==''&& isset($_GET['gia']) && $_GET['gia']=='thap_cao' && $_GET['gia'] !='') 
                            {
                                $statement = $this->db->prepare('SELECT * FROM san_pham WHERE danh_muc_id= 1 ORDER BY gia ');
                                $statement->execute();
                                return $statement->fetchAll(PDO::FETCH_OBJ);
                            } 
        }

        // public function filter_laptop(string $hang,string $gia): array
        // {   
        //     if($hang=='' && $gia=='') {
        //         $statement = $this->db->prepare('SELECT * FROM san_pham WHERE danh_muc_id= 2');
        //         $statement->execute();
        //         return $statement->fetchAll(PDO::FETCH_OBJ);
        //     }else if(isset($_GET['hang']) && $_GET['hang']!=''&& isset($_GET['gia']) && $_GET['gia']=='thap_cao' && $_GET['gia'] !='')
        //         {
        //             $statement = $this->db->prepare('SELECT * FROM san_pham WHERE ten_san_pham LIKE :hang ORDER BY gia');
        //             $statement->execute([
        //                 ':hang' => "%$hang%"
        //             ]);
        //             return $statement->fetchAll(PDO::FETCH_OBJ);
        //         } else if(isset($_GET['hang']) && $_GET['hang']!=''&& isset($_GET['gia']) && $_GET['gia']=='cao_thap' && $_GET['gia'] !='') 
        //             {
        //                 $statement = $this->db->prepare('SELECT * FROM san_pham WHERE ten_san_pham LIKE :hang ORDER BY gia DESC');
        //                 $statement->execute([
        //                     ':hang' => "%$hang%"
        //                 ]);
        //                 return $statement->fetchAll(PDO::FETCH_OBJ);
        //             } 
        //                 else if(isset($_GET['hang']) && $_GET['hang']!=''&& $_GET['gia'] =='')
        //                 {
        //                     $statement = $this->db->prepare('SELECT * FROM san_pham WHERE ten_san_pham LIKE :hang');
        //                     $statement->execute([
        //                         ':hang' => "%$hang%"
        //                     ]);
        //                     return $statement->fetchAll(PDO::FETCH_OBJ);
        //                 } else if(isset($_GET['hang']) && $_GET['hang']==''&& isset($_GET['gia']) && $_GET['gia']=='cao_thap' && $_GET['gia'] !='')
        //                     {
        //                         $statement = $this->db->prepare('SELECT * FROM san_pham ORDER BY gia DESC');
        //                         $statement->execute();
        //                         return $statement->fetchAll(PDO::FETCH_OBJ);
        //                     } else if(isset($_GET['hang']) && $_GET['hang']==''&& isset($_GET['gia']) && $_GET['gia']=='thap_cao' && $_GET['gia'] !='') 
        //                         {
        //                             $statement = $this->db->prepare('SELECT * FROM san_pham ORDER BY gia ');
        //                             $statement->execute();
        //                             return $statement->fetchAll(PDO::FETCH_OBJ);
        //                         } 
        //     }

            public function filter_laptop(string $hang,string $gia): array
            {   
                if($hang=='' && $gia=='') {
                    $statement = $this->db->prepare('SELECT * FROM san_pham WHERE danh_muc_id= 2');
                    $statement->execute();
                    return $statement->fetchAll(PDO::FETCH_OBJ);
                }else if(isset($_GET['hang']) && $_GET['hang']!=''&& isset($_GET['gia']) && $_GET['gia']=='thap_cao' && $_GET['gia'] !='')
                    {
                        $statement = $this->db->prepare('SELECT * FROM san_pham WHERE (ten_san_pham LIKE :hang AND danh_muc_id=2)  ORDER BY gia');
                        $statement->execute([
                            ':hang' => "%$hang%"
                        ]);
                        return $statement->fetchAll(PDO::FETCH_OBJ);
                    } else if(isset($_GET['hang']) && $_GET['hang']!=''&& isset($_GET['gia']) && $_GET['gia']=='cao_thap' && $_GET['gia'] !='') 
                        {
                            $statement = $this->db->prepare('SELECT * FROM san_pham WHERE (ten_san_pham LIKE :hang AND danh_muc_id=2) ORDER BY gia DESC');
                            $statement->execute([
                                ':hang' => "%$hang%"
                            ]);
                            return $statement->fetchAll(PDO::FETCH_OBJ);
                        } 
                            else if(isset($_GET['hang']) && $_GET['hang']!=''&& $_GET['gia'] =='')
                            {
                                $statement = $this->db->prepare('SELECT * FROM san_pham WHERE (ten_san_pham LIKE :hang AND danh_muc_id=2)');
                                $statement->execute([
                                    ':hang' => "%$hang%"
                                ]);
                                return $statement->fetchAll(PDO::FETCH_OBJ);
                            } else if(isset($_GET['hang']) && $_GET['hang']==''&& isset($_GET['gia']) && $_GET['gia']=='cao_thap' && $_GET['gia'] !='')
                                {
                                    $statement = $this->db->prepare('SELECT * FROM san_pham WHERE danh_muc_id= 2 ORDER BY gia DESC');
                                    $statement->execute();
                                    return $statement->fetchAll(PDO::FETCH_OBJ);
                                } else if(isset($_GET['hang']) && $_GET['hang']==''&& isset($_GET['gia']) && $_GET['gia']=='thap_cao' && $_GET['gia'] !='') 
                                    {
                                        $statement = $this->db->prepare('SELECT * FROM san_pham WHERE danh_muc_id= 2 ORDER BY gia ');
                                        $statement->execute();
                                        return $statement->fetchAll(PDO::FETCH_OBJ);
                                    } 
                }
}