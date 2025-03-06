<?php
require_once 'app/models/User.php';
require_once 'app/models/Order.php';
require_once 'app/models/Shoe.php';
require_once 'app/models/Product.php';

class AdminController {

    private $pdo; // تعريف المتغير الخاص بقاعدة البيانات

    public function __construct($pdo) {
        $this->pdo = $pdo; // تعيين قيمة $pdo من خارج الكلاس
    }

    // الدالة لعرض لوحة التحكم (Dashboard)
    public function dashboard() {
        session_start();

        if (!isset($_SESSION['role']) || $_SESSION['role'] === 'user'){
            header('Location: /project/login');
            exit;
        }

        
        global $pdo;

        // جلب عدد المستخدمين
        $userModel = new User($pdo);
        $totalUsers = $userModel->getTotalUsers();

        // جلب عدد الطلبات
        $orderModel = new Order($pdo);
        $totalOrders = $orderModel->getTotalOrders();

        // جلب عدد المنتجات
        $shoeModel = new Shoe($pdo);
        $totalShoes = $shoeModel->getTotalShoes();

        // جلب أحدث 5 طلبات
        $recentOrders = $orderModel->getRecentOrders();

        // جلب أحدث 5 منتجات مضافة
        $recentShoes = $shoeModel->getRecentShoes();

        // إرسال البيانات إلى العرض
        require 'app/views/admin/dashboard.php';
    }

    // الدالة لإدارة المستخدمين
    public function manageUsers() {
        session_start();

        // التأكد من أن المستخدم هو مشرف
        if (!isset($_SESSION['role']) || $_SESSION['role'] === 'user'){
            header('Location: /project/login');
            exit;
        }

        global $pdo;
        $userModel = new User($pdo);

        // استرجاع جميع المستخدمين
        $users = $userModel->getAllUsers();

        // تحميل العرض (View) لإدارة المستخدمين
        require 'app/views/admin/manage_users.php';
    }


    public function deleteUser() {
        session_start();

        // التأكد من أن المستخدم هو مشرف
        if (!isset($_SESSION['role']) || $_SESSION['role'] === 'user'){
            header('Location: /project/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
            $userId = $_POST['user_id'];

            $userModel = new User($this->pdo);
            if ($userModel->deleteUser($userId)) {
                $_SESSION['success'] = "تم حذف المستخدم بنجاح.";
            } else {
                $_SESSION['error'] = "حدث خطأ أثناء الحذف.";
            }
        }

        header('Location: /project/admin/users');
        exit;
    }

    // الدالة لإدارة الطلبات
    public function manageOrders() {
        session_start();

       // التأكد من أن المستخدم هو مشرف
        if (!isset($_SESSION['role']) || $_SESSION['role'] === 'user'){
            header('Location: /project/login');
            exit;
        }

        global $pdo;
        $orderModel = new Order($pdo);

        // استرجاع جميع الطلبات
        $orders = $orderModel->getAllOrders();

        // تحميل العرض (View) لإدارة الطلبات
        require 'app/views/admin/manage_orders.php';
    }


    // حذف طلب معين
    public function deleteOrder() {
        session_start();

        // التأكد من أن المستخدم هو مشرف
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /project/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
            $orderId = $_POST['order_id'];

            $orderModel = new Order($this->pdo);
            if ($orderModel->deleteOrder($orderId)) {
                $_SESSION['success'] = "تم حذف الطلب بنجاح.";
            } else {
                $_SESSION['error'] = "حدث خطأ أثناء حذف الطلب.";
            }
        }

        header('Location: /project/admin/orders');
        exit;
    }


    // عرض جميع المنتجات في لوحة التحكم
    public function manageProducts() {
        session_start();

        // التأكد من أن المستخدم هو مشرف
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /project/login');
            exit;
        }

        $productModel = new Product($this->pdo);
        $products = $productModel->getAllProducts();

        require 'app/views/admin/manage_products.php';
    }

    // حذف منتج معين
    public function deleteProduct() {
        session_start();

        // التأكد من أن المستخدم هو مشرف
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /project/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
            $productId = $_POST['product_id'];

            $productModel = new Product($this->pdo);
            if ($productModel->deleteProduct($productId)) {
                $_SESSION['success'] = "تم حذف المنتج بنجاح.";
            } else {
                $_SESSION['error'] = "حدث خطأ أثناء حذف المنتج.";
            }
        }

        header('Location: /project/admin/products');
        exit;
    }



    // عرض نموذج إضافة المنتج
    public function showAddProductForm() {
        session_start();

        // التأكد من أن المستخدم هو مشرف
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /project/login');
            exit;
        }

        require 'app/views/admin/add_product.php';
    }



       // معالجة إضافة المنتج
    public function addProduct() {
        session_start();

        // التأكد من أن المستخدم هو مشرف
        if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
            header('Location: /project/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $price = $_POST['price'];
            $stock = $_POST['stock'];
            $description = $_POST['description'];

            // معالجة رفع الصورة
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = time() . '_' . $_FILES['image']['name'];
                move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $image);
            }

            $productModel = new Product($this->pdo);
            if ($productModel->addProduct($name, $price, $stock, $description, $image)) {
                $_SESSION['success'] = "تمت إضافة المنتج بنجاح.";
            } else {
                $_SESSION['error'] = "حدث خطأ أثناء إضافة المنتج.";
            }

            header('Location: /project/admin/products');
            exit;
        }
    }
}
?>
