<?php



// التحقق من دور المستخدم
$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $isAdmin ? "لوحة تحكم المشرف" : "المتجر" ?></title>
    <link rel="stylesheet" href="app/public/css/home.css">
</head>
<body>

<!-- الشريط الجانبي للمشرف فقط -->
<?php if ($isAdmin): ?>
    <div class="admin-container">
        <nav class="sidebar">
            <h2>لوحة التحكم</h2>
            <ul>
                <li><a href="/project/admin/dashboard">الرئيسية</a></li>
                <li><a href="/project/admin/users">إدارة المستخدمين</a></li>
                <li><a href="/project/admin/orders">إدارة الطلبات</a></li>
                <li><a href="/project/admin/products"> عرض المنتجات </a></li>
                <li><a href="/project/admin/addProduct"> اضافة منتج </a></li>
            </ul>
        </nav>
    </div>
<?php endif; ?>

<!-- القائمة العلوية -->
<div class="navbar">
    <a href="https://www.facebook.com/share/1DSFwt6xUg/" class="navbar-logo" target="_blank">
        <img src="app/public/images/logo.jpg" alt="Logo" />
    </a>
    


    
<div class="navbar-center">
    <?php if (!isset($_SESSION['email'])): ?>
        <!-- عرض Welcome User إذا لم يكن المستخدم مسجل -->
        <h2 style="color: white;">Welcome User</h2>
    <?php else: ?>
        <!-- عرض اسم المستخدم إذا كان مسجل -->
        <h2 style="color: white;">Welcome : <?= htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></h2>
    <?php endif; ?>
</div>


    
    <div class="navbar-links">


    

        <a href="/project/">home</a>

        <a href="/project/my-orders">My Orders</a>
        <?php if (!isset($_SESSION['email'])): ?>
            <a href="/project/login">Login</a>
        <?php else: ?>
            <a href="/project/logout">Logout</a>
        <?php endif; ?>
    </div>
</div>
<!-- عرض المنتجات -->
<div class="shoe-container">
    <?php foreach ($shoes as $shoe): ?>
        <div class="shoe-item">
            <img src="<?= htmlspecialchars($shoe['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="<?= htmlspecialchars($shoe['name'], ENT_QUOTES, 'UTF-8'); ?>" style="width: 200px; height: auto;">
            <h3><?= htmlspecialchars($shoe['name'], ENT_QUOTES, 'UTF-8'); ?></h3>
            <p>Price: $<?= number_format($shoe['price'], 2); ?></p>
            <a href="/project/confirm-purchase?shoe_id=<?= htmlspecialchars($shoe['id'], ENT_QUOTES, 'UTF-8'); ?>" class="buy-button">Buy</a>
        </div>
    <?php endforeach; ?>
</div>










</body>
</html>
