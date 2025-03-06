<?php
class Product {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // استرجاع جميع المنتجات
    public function getAllProducts() {
        $stmt = $this->pdo->query("SELECT id, name, price, stock, description, image, created_at FROM shoes ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    

    // حذف منتج بناءً على ID
    public function deleteProduct($productId) {
        $stmt = $this->pdo->prepare("DELETE FROM shoes WHERE id = :id");
        return $stmt->execute(['id' => $productId]);
    }


    // إضافة منتج جديد
    public function addProduct($name, $price, $stock, $description, $image) {
        $stmt = $this->pdo->prepare("INSERT INTO shoes (name, price, stock, description, image, created_at) VALUES (:name, :price, :stock, :description, :image, NOW())");
        return $stmt->execute([
            'name' => $name,
            'price' => $price,
            'stock' => $stock,
            'description' => $description,
            'image' => $image
        ]);
    }
}
?>
