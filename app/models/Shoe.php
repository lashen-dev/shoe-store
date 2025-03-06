<?php
class Shoe {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }


    public function getTotalShoes() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM shoes");
        $row = $stmt->fetch();
        return $row['count'];
    }


    // استرجاع كل الأحذية
    public function getAllShoes() {
        $stmt = $this->pdo->query("SELECT * FROM shoes");
        return $stmt->fetchAll();
    }



    public function getRecentShoes() {
        $stmt = $this->pdo->query("
            SELECT 
    id, 
    name, 
    price, 
    stock, 
    description, 
    image, 
    created_at 
FROM shoes 
ORDER BY created_at DESC 
LIMIT 5;

        ");
        return $stmt->fetchAll();
    }

    // استرجاع حذاء واحد بناءً على معرفه
    public function getShoeById($shoeId) {
        $stmt = $this->pdo->prepare("SELECT * FROM shoes WHERE id = :id");
        $stmt->bindParam(':id', $shoeId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(); // جلب صف واحد فقط
    }
}
?>

