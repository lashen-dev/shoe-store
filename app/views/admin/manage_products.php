<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المنتجات</title>
    <link rel="stylesheet" href="../app/public/admin_css/manage_products.css">


</head>
<body>
    <div class="container">
        <h2>إدارة المنتجات</h2>

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
                    <th>اسم المنتج</th>
                    <th>السعر</th>
                    <th>المخزون</th>
                    <th>الوصف</th>
                    <th>الصورة</th>
                    <th>تاريخ الإضافة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?> $</td>
                        <td><?php echo htmlspecialchars($product['stock']); ?></td>
                        <td><?php echo htmlspecialchars($product['description']); ?></td>
                        <td><img src="/project/uploads/<?php echo htmlspecialchars($product['image']); ?>" width="50"></td>
                        <td><?php echo date("d M Y", strtotime($product['created_at'])); ?></td>
                        <td>
                            <form method="POST" action="/project/admin/delete-product" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
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
