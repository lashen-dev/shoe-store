<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلباتي</title>
    <link rel="stylesheet" href="app/public/css/myorder.css">
    
</head>
<body>
    <div class="container">
        <h1>طلباتي</h1>

        <?php if (!empty($orders)): ?>
            <table>
                <thead>
                    <tr>
                        <th>رقم الطلب</th>
                        <th>اسم المنتج</th>
                        <th>الكمية</th>
                        <th>السعر الإجمالي</th>
                        <th>إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td><?= htmlspecialchars($order['shoe_name']) ?></td>
                            <td><?= htmlspecialchars($order['quantity']) ?></td>
                            <td>$<?= htmlspecialchars(number_format($order['total_price'], 2)) ?></td>
                            <td>
                                <form action="/project/delete_order" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا الطلب؟');">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
                                    <input type="hidden" name="order_id" value="<?= htmlspecialchars($order['id']) ?>">
                                    <button type="submit" class="delete-button">حذف</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-orders">ليس لديك أي طلبات حالياً.</p>
        <?php endif; ?>

        <a href="/project/" class="back-link">الرجوع إلى الصفحة الرئيسية</a>
    </div>
</body>
</html>
