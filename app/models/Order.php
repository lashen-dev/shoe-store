<?php
class Order {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }



    public function getTotalOrders() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM orders");
        $row = $stmt->fetch();
        return $row['count'];
    }


    // استرجاع جميع الطلبات مع بيانات المستخدم والمنتج
    public function getAllOrders() {
        $stmt = $this->pdo->query("
            SELECT orders.id, users.name AS user_name, shoes.name AS shoe_name, orders.quantity, orders.total_price, orders.created_at 
            FROM orders 
            JOIN users ON orders.user_id = users.id 
            JOIN shoes ON orders.shoe_id = shoes.id 
            ORDER BY orders.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

    public function getRecentOrders() {
        $stmt = $this->pdo->query("
            SELECT orders.id, users.name AS user_name, shoes.name AS shoe_name, 
                   orders.quantity, orders.total_price, orders.created_at 
            FROM orders
            JOIN users ON orders.user_id = users.id
            JOIN shoes ON orders.shoe_id = shoes.id
            ORDER BY orders.created_at DESC
            LIMIT 5
        ");
        return $stmt->fetchAll();
    }
    


    // دالة لإنشاء الطلب والتحقق من السعر والمخزون
    public function createOrder($userId, $shoeId, $quantity) {
        // بدء الجلسة لتخزين الرسائل
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // استعلام لجلب السعر والمخزون
        $stmt = $this->pdo->prepare("SELECT price, stock FROM shoes WHERE id = :id");
        $stmt->bindParam(':id', $shoeId, PDO::PARAM_INT);
        $stmt->execute();
        $shoe = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$shoe) {
            $_SESSION['order_message'] = 'الحذاء غير موجود.';
            return ['success' => false];
        }

        // التحقق من المخزون
        if ($quantity > $shoe['stock']) {
            $_SESSION['order_message'] = 'الكمية المطلوبة غير متوفرة.';
            return ['success' => false];
        }

        // حساب السعر الإجمالي
        $totalPrice = $quantity * $shoe['price'];

        // إنشاء الطلب وتخزينه في قاعدة البيانات
        $orderStmt = $this->pdo->prepare("INSERT INTO orders (user_id, shoe_id, quantity, total_price) VALUES (:user_id, :shoe_id, :quantity, :total_price)");
        $orderStmt->execute([
            'user_id' => $userId,
            'shoe_id' => $shoeId,
            'quantity' => $quantity,
            'total_price' => $totalPrice
        ]);

        // تحديث المخزون بعد الطلب
        $newStock = $shoe['stock'] - $quantity;
        $updateStmt = $this->pdo->prepare("UPDATE shoes SET stock = :new_stock WHERE id = :id");
        $updateStmt->bindParam(':new_stock', $newStock, PDO::PARAM_INT);
        $updateStmt->bindParam(':id', $shoeId, PDO::PARAM_INT);
        $updateStmt->execute();

        // تخزين رسالة النجاح في الجلسة
        $_SESSION['order_message'] = "تم تأكيد الشراء. السعر الإجمالي: $" . number_format($totalPrice, 2);

        return ['success' => true];
    }






    public function getOrdersByUserId($userId) {
        $stmt = $this->pdo->prepare("
            SELECT orders.id, orders.shoe_id, orders.quantity, orders.total_price, shoes.name AS shoe_name
            FROM orders
            JOIN shoes ON orders.shoe_id = shoes.id
            WHERE orders.user_id = :user_id
            ORDER BY orders.id DESC
        ");
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }










    public function deleteOrder($orderId) {
        // تأكد من أن $orderId هو رقم صحيح
        $orderId = intval($orderId);

        // جملة SQL لحذف الطلب من الجدول
        $query = "DELETE FROM orders WHERE id = :order_id";
        $stmt = $this->pdo->prepare($query);

        // ربط المعامل
        $stmt->bindParam(':order_id', $orderId, PDO::PARAM_INT);

        // تنفيذ الاستعلام وإرجاع النتيجة
        return $stmt->execute();
    }
    
}
?>
