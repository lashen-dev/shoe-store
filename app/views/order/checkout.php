<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link rel="stylesheet" href="app/public/css/checkout.css">

 
</head>
<body>
    <div class="confirmation-box">
        <h1>Order Status</h1>
        <p>
            <?= isset($_SESSION['order_message']) ? htmlspecialchars($_SESSION['order_message']) : 'لا توجد رسالة طلب.'; ?>
        </p>
        <a href="/project" class="button">Back to Products</a>
    </div>
</body>
</html>
