<?php
require_once 'app/models/Order.php';

class OrderController {
    public function checkout() {
        session_start();
    
        // التأكد من أن المستخدم قام بتسجيل الدخول
        if (!isset($_SESSION['email'])) {
            header('Location: /project/login');  // إذا لم يكن المستخدم مسجلاً دخوله، يتم توجيهه إلى صفحة تسجيل الدخول
            exit;
        }
    
        // التعامل مع الطلب
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $pdo;  // استخدام الاتصال بقاعدة البيانات
            $orderModel = new Order($pdo);
    
            // التحقق من الرمز المميز
            $csrfToken = $_POST['csrf_token'] ?? '';
            if (empty($csrfToken) || $csrfToken !== $_SESSION['csrf_token']) {
                $_SESSION['flash_errors'][] = 'خطأ: طلب غير صالح (CSRF).';
                header('Location: /project/register', true, 303);

                exit();
            }
    
            // التأكد من أن قيمة user_id ليست فارغة أو NULL
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['flash_errors'][] = "يجب عليك تسجيل الدخول أولاً.";
                header('Location: /project/register', true, 303);

                exit();
            }
    
            // التحقق من وجود الحقول shoe_id و quantity
            if (empty($_POST['shoe_id']) || !is_numeric($_POST['shoe_id'])) {
                $_SESSION['flash_errors'][] =  "يرجى تحديد المنتج بشكل صحيح.";
                header('Location: /project/register', true, 303);

                exit();
            }
    
            if (empty($_POST['quantity']) || !is_numeric($_POST['quantity']) || $_POST['quantity'] <= 0) {
                $_SESSION['flash_errors'][] =  "يرجى تحديد كمية صحيحة.";
                header('Location: /project/register', true, 303);

                exit();
            }
    
            // إنشاء الطلب
            $result = $orderModel->createOrder($_SESSION['user_id'], $_POST['shoe_id'], $_POST['quantity']);
            if (!$result) {
                $_SESSION['flash_errors'][] =  "حدث خطأ أثناء معالجة الطلب. يرجى المحاولة لاحقاً.";
                header('Location: /project/register', true, 303);

                exit();
                // إعادة توجيه أو عرض رسالة تأكيد
            }
        }
    
        // تحميل العرض (View)
        require 'app/views/order/checkout.php';
    }
    




    public function myOrders() {
        session_start();
    
        // تحقق من تسجيل الدخول
        if (!isset($_SESSION['email']) || !isset($_SESSION['user_id'])) {
            header('Location: /project/login');
            exit;
        }
    
        global $pdo; // الاتصال بقاعدة البيانات
        $orderModel = new Order($pdo);
    
        // استرجاع الطلبات
        $userId = $_SESSION['user_id'];
        $orders = $orderModel->getOrdersByUserId($userId);
    
        // تحميل العرض
        require 'app/views/order/my_orders.php';
    }







    public function deleteOrder() {
        session_start();
    
        // التأكد من أن المستخدم قام بتسجيل الدخول
        if (!isset($_SESSION['email'])) {
            header('Location: /project/login');  // إذا لم يكن المستخدم مسجلاً دخوله، يتم توجيهه إلى صفحة تسجيل الدخول
            exit;
        }
    
        // التأكد من أن الطلب يحتوي على معرف الطلب
        if (isset($_POST['order_id']) && isset($_POST['csrf_token'])) {
            $csrfToken = $_POST['csrf_token'];
    
            // التحقق من صحة الرمز المميز (CSRF)
            if (empty($csrfToken) || $csrfToken !== $_SESSION['csrf_token']) {
                $_SESSION['flash_errors'][] = 'خطأ: طلب غير صالح (CSRF).';
                header('Location: /project/register', true, 303);

                exit();
            }
    
            $orderId = $_POST['order_id'];
    
            // التحقق من أن معرف الطلب هو قيمة صحيحة
            if (empty($orderId) || !is_numeric($orderId)) {
                $_SESSION['flash_errors'][] =  "معرف الطلب غير صالح.";
                header('Location: /project/register', true, 303);

                exit();
            }
    
            global $pdo;  // الاتصال بقاعدة البيانات
            $orderModel = new Order($pdo);  // تهيئة نموذج الطلب
    
            // تنفيذ الحذف باستخدام نموذج (Model)
            $isDeleted = $orderModel->deleteOrder($orderId);
    
            // العودة إلى صفحة الطلبات بعد الحذف
            if ($isDeleted) {
                header("Location: /project/my-orders");  // إعادة توجيه إلى صفحة الطلبات
                exit();
            } else {
                // إذا فشل الحذف، أضف رسالة خطأ واضحة
                $_SESSION['flash_errors'][] =  "حدث خطأ أثناء محاولة حذف الطلب. يرجى المحاولة لاحقاً.";
                header('Location: /project/register', true, 303);

                exit();
            }
        } else {
            $_SESSION['flash_errors'][] =  "معرف الطلب أو الرمز المميز غير موجود.";
            header('Location: /project/register', true, 303);

            exit();
        }
    }
    
    
    
    
}
?>
