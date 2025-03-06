<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة منتج جديد</title>
    <link rel="stylesheet" href="../app/public/admin_css/add_product.css">


    
</head>
<body>
    <div class="container">
        <h2>إضافة منتج جديد</h2>

        <?php if (isset($_SESSION['success'])): ?>
            <p class="success"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <p class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></p>
        <?php endif; ?>

        <form method="POST" action="/project/admin/addProduct" enctype="multipart/form-data">
            <label>اسم المنتج:</label>
            <input type="text" name="name" required>

            <label>السعر:</label>
            <input type="number" name="price" step="0.01" required>

            <label>المخزون:</label>
            <input type="number" name="stock" required>

            <label>الوصف:</label>
            <textarea name="description" required></textarea>

            <label>الصورة:</label>
            <input type="file" name="image" accept="image/*">

            <button type="submit">إضافة المنتج</button>
        </form>

        <a href="/project/admin/products" class="back-btn">العودة إلى قائمة المنتجات</a>
    </div>
</body>
</html>
