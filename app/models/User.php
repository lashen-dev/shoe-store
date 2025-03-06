<?php

require_once "config.php";
class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    
    public function getTotalUsers() {
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM users");
        $row = $stmt->fetch();
        return $row['count'];
    }


    // جلب جميع المستخدمين باستثناء المشرفين
    public function getAllUsers() {
        $stmt = $this->pdo->query("SELECT id, name, email, role FROM users WHERE role != 'admin'");
        return $stmt->fetchAll();
    }

    // حذف مستخدم بواسطة ID
    public function deleteUser($userId) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$userId]);
    }


    


    public function emailExists($email) {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetchColumn() > 0; // إرجاع true إذا كان البريد موجودًا
    }

    
    public function register($name, $email, $password) {
        // التحقق مما إذا كان البريد الإلكتروني موجودًا بالفعل
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $emailExists = $stmt->fetchColumn(); // إرجاع عدد السجلات التي تحتوي على البريد الإلكتروني
    
    
        // إذا لم يكن البريد الإلكتروني موجودًا، متابعة التسجيل
        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        return $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT)
        ]);    
        
    }
    

    public function login($email, $password) {
        // استعلام للتحقق من البريد الإلكتروني وكلمة المرور
        $stmt = $this->pdo->prepare("SELECT id, name , email, password , role FROM users WHERE email = :email LIMIT 1");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // التحقق من كلمة المرور
        if ($user && !empty($password) && password_verify($password, $user['password'])) {
            return $user;
        }
        

        return false;  // إرجاع false في حال فشل التحقق
    }
}
?>