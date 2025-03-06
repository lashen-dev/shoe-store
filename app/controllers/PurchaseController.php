<?php
require_once 'app/models/Shoe.php';

class PurchaseController {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function showConfirmPage() {
        session_start();

        if (!isset($_SESSION['email'])) {
            header('Location: /project/login');
            exit;
        }
        
        if (!isset($_GET['shoe_id'])) {
            die('No shoe selected.');
        }

        $shoeId = intval($_GET['shoe_id']);

        $shoeModel = new Shoe($this->pdo);
        $shoe = $shoeModel->getShoeById($shoeId);

        if (!$shoe) {
            die('Shoe not found.');
        }

        

        require 'app/views/purchase/confirm.php';
    

    }

    public function confirmPurchase() {
        if (!isset($_POST['shoe_id'], $_POST['quantity'])) {
            die('Invalid request.');
        }

        $shoeId = intval($_POST['shoe_id']);
        $quantity = intval($_POST['quantity']);

        $shoeModel = new Shoe($this->pdo);
        $shoe = $shoeModel->getShoeById($shoeId);

        if (!$shoe || $quantity > $shoe['stock']) {
            die('Invalid quantity or insufficient stock.');
        }

        // تحديث المخزون
        $newStock = $shoe['stock'] - $quantity;
        $stmt = $this->pdo->prepare("UPDATE shoes SET stock = :stock WHERE id = :id");
        $stmt->execute(['stock' => $newStock, 'id' => $shoeId]);

        echo "Purchase confirmed! Thank you for your order.";
    }
}
?>