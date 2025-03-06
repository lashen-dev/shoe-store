<?php
require_once 'app/models/User.php';

class AuthController {

    private function checkCsrfToken($token) {
        if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
            $_SESSION['flash_errors'][] = 'طلب غير صالح. يرجى المحاولة مرة أخرى.';
            header('Location: /project/login');
            exit(); // ⬅️ إنهاء التنفيذ فورًا
        }
        unset($_SESSION['csrf_token']); // منع إعادة الاستخدام
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // توليد توكن جديد
    }
    


    // دالة للتحقق من قوة كلمة المرور
    private function validatePassword($password) {
        $errors = [];
        $passwordChecks = [
            'يجب أن تكون كلمة المرور 8 أحرف على الأقل.' => strlen($password) < 8,
            'يجب أن تحتوي كلمة المرور على حرف كبير واحد على الأقل.' => !preg_match('/[A-Z]/', $password),
            'يجب أن تحتوي كلمة المرور على حرف صغير واحد على الأقل.' => !preg_match('/[a-z]/', $password),
            'يجب أن تحتوي كلمة المرور على رقم واحد على الأقل.' => !preg_match('/[0-9]/', $password),
            'يجب أن تحتوي كلمة المرور على رمز خاص واحد على الأقل.' => !preg_match('/[\W]/', $password),
        ];
    
        foreach ($passwordChecks as $message => $condition) {
            if ($condition) {
                $errors[] = $message;
            }
        }
    
        return $errors;
    }

    
    
    public function login() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->checkCsrfToken($_POST['csrf_token'] ?? '');
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = trim($_POST['password'] ?? '');

            $errors = [];
    
            if (empty($email) || empty($password)) {
                $errors[] = 'يرجى إدخال البريد الإلكتروني وكلمة المرور معاً.';
            }
    
            // التحقق من صحة البريد الإلكتروني (بشرط أن لا يكون فارغًا)
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'عنوان البريد الإلكتروني غير صالح.';
            }
    
            // إذا كانت هناك أخطاء، يتم حفظها وإعادة التوجيه
            if (!empty($errors)) {
                $_SESSION['flash_errors'] = $errors;
                header('Location: /project/login', true, 303);
                exit();

            }
    
            global $pdo;
            $userModel = new User($pdo);
            // محاولة تسجيل الدخول
            $user = $userModel->login($email, $password);
    
            if (!$user) {
                $_SESSION['flash_errors'][] = 'تسجيل دخول غير ناجح يرجي التاكد من البيانات';
                header('Location: /project/login');
                exit();
            }
    
            // حفظ بيانات المستخدم في الجلسة
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role']; // حفظ دور المستخدم في الجلسة
    
            header('Location: /project', true, 303);
            exit();
        }
    
        // عرض صفحة تسجيل الدخول
        require 'app/views/auth/login.php';
    }
    

    public function register() {
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            global $pdo;
            $userModel = new User($pdo);
            $this->checkCsrfToken($_POST['csrf_token'] ?? '');
            $name = htmlspecialchars(trim($_POST['name'] ?? ''), ENT_QUOTES, 'UTF-8');
            $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';
            $errors = [];
    
            // التحقق من المدخلات الأساسية
            if (empty($name) || empty($email) || empty($password)) {
                $errors[] = 'يرجى ملء جميع الحقول.';
            }
    
            // التحقق من صحة البريد الإلكتروني (بشرط أن لا يكون فارغًا)
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'عنوان البريد الإلكتروني غير صالح.';
            } elseif ($userModel->emailExists($email)) {
                $errors[] = 'البريد الإلكتروني مسجل بالفعل، الرجاء استخدام بريد آخر.';
            }
    
            // التحقق من قوة كلمة المرور
            $passwordErrors = $this->validatePassword($password);
            if (count($passwordErrors) > 1 && !empty($password)) {
                $errors[] = 'يجب أن تكون كلمة المرور قوية وتحقق جميع المتطلبات.';
            } elseif (count($passwordErrors) === 1) {
                $errors[] = $passwordErrors[0];
            }
    
            // إذا كانت هناك أخطاء، يتم حفظها وإعادة التوجيه
            if (!empty($errors)) {
                $_SESSION['flash_errors'] = $errors;
                header('Location: /project/register', true, 303);
                exit();
                
            }
    
            
            
            // محاولة تسجيل المستخدم
            if ($userModel->register($name, $email, $password)) {
                header('Location: /project/login');
                exit();
            } else {
                $_SESSION['flash_errors'][] = 'تسجيل غير ناجح يرجي التاكد من البيانات';
                header('Location: /project/register', true, 303);
                exit();
            }
        }
    
        require 'app/views/auth/register.php';
    }
    
    
    
    
    




    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /project/login');
        exit();

    }

}
?>