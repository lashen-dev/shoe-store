<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة الطلبات</title>
    <link rel="stylesheet" href="../app/public/admin_css/manage_orders.css">


</head>
<body>
    <div class="container">
        <h2>إدارة الطلبات</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <table>
            <thead>
                <tr>
                    <th>المعرف</th>
                    <th>اسم المستخدم</th>
                    <th>اسم المنتج</th>
                    <th>الكمية</th>
                    <th>السعر الإجمالي</th>
                    <th>تاريخ الطلب</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($order['id']); ?></td>
                        <td><?php echo htmlspecialchars($order['user_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['shoe_name']); ?></td>
                        <td><?php echo htmlspecialchars($order['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($order['total_price']); ?> $</td>
                        <td><?php echo date("d M Y", strtotime($order['created_at'])); ?></td>
                        <td>
                            <form method="POST" action="/project/admin/delete-order" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟');">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <button type="submit" class="delete-btn">حذف</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <a href="/project/admin/dashboard" class="back-btn">العودة إلى لوحة التحكم</a>
    </div>

</body>
</html>
