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
        // Thêm các quy tắc kiểm tra hợp lệ tại đây
        return true;
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
            $statement = $this->db->prepare('UPDATE san_pham SET ten_san_pham = :name, mo_ta = :description, gia = :price, so_luong = :quantity, anh = :image, danh_muc_id = :category_id, updated_at = NOW() WHERE san_pham_id = :id');
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
            $statement = $this->db->prepare('INSERT INTO san_pham (ten_san_pham, mo_ta, gia, so_luong, anh, danh_muc_id, created_at, updated_at) VALUES (:name, :description, :price, :quantity, :image, :category_id, NOW(), NOW())');
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
            $this->fill($row);
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

}
