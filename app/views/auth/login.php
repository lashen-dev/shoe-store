<?php


if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // إنشاء رمز CSRF عشوائي
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="app/public/css/login.css">

</head>
<body>

    <div class="form-container">
        <h2>Login</h2>

        <!-- عرض الأخطاء -->
        <?php if (isset($_SESSION['flash_errors']) && !empty($_SESSION['flash_errors'])): ?>
            <div id="error-messages">
                <?php foreach ($_SESSION['flash_errors'] as $error): ?>
                    <div class="error-container">
                        <?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?>
                        <span class="close-btn" onclick="this.parentElement.style.display='none'">&times;</span>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php unset($_SESSION['flash_errors']); // حذف الأخطاء بعد عرضها ?>
        <?php endif; ?>

        <form method="POST" action="/project/login">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
            <input type="email" name="email" placeholder="Email" >
            <input type="password" name="password" placeholder="Password" >
            <button type="submit">Login</button>
        </form>

        <div class="register-link">
            <p>Don't have an account? <a href="/project/register">Register</a></p>
        </div>
    </div>

    <!-- JavaScript لإخفاء الرسائل بعد 5 ثوانٍ -->
    <script>
        setTimeout(() => {
            document.querySelectorAll('.error-container').forEach(error => {
                error.style.animation = 'fadeOut 0.5s ease-in-out forwards';
                setTimeout(() => error.remove(), 500);
            });
        }, 5000);
    </script>

</body>
</html>
