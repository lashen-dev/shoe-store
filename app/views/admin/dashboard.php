<?php
// التأكد من أن المستخدم مشرف قبل تحميل الصفحة
if (!isset($_SESSION['role'])) {
    header('Location: /project/login');
    exit;
}

if (!isset($totalShoes)) {
    $totalShoes = 0;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - المشرف</title>
    <link rel="stylesheet" href="../app/public/admin_css/dashboard.css">


</head>
<body>
    <div class="main-content">
        <header>
            <h1>مرحبًا بك في لوحة التحكم</h1>
            <p>مرحبا، <?php echo $_SESSION['email']; ?>. مرحبًا بك في لوحة تحكم المشرف.</p>
        </header>

        <section class="statistics">
            <div class="stat-item">
                <h3>إجمالي المستخدمين</h3>
                <p><?php echo $totalUsers; ?></p>
            </div>
            <div class="stat-item">
                <h3>إجمالي الطلبات</h3>
                <p><?php echo $totalOrders; ?></p>
            </div>
            <div class="stat-item">
                <h3>إجمالي المنتجات</h3>
                <p><?php echo $totalShoes; ?></p>
            </div>
        </section>

        <section class="recent-activity">
            <h2>الأنشطة الأخيرة</h2>
            <ul>
                <?php foreach ($recentOrders as $order): ?>
                    <li>طلب جديد من المستخدم <strong><?php echo htmlspecialchars($order['user_name']); ?></strong> لشراء <strong><?php echo htmlspecialchars($order['shoe_name']); ?></strong> بتاريخ <?php echo date("d M Y", strtotime($order['created_at'])); ?>.</li>
                <?php endforeach; ?>
            </ul>
        </section>

        <!-- زر الرجوع إلى الموقع -->
        <div class="back-button">
            <a href="/project/" class="btn">الرجوع إلى الموقع</a>
        </div>
    </div>

    
</body>
</html>
