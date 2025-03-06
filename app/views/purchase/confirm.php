<?php if ($shoe): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Purchase</title>
    <link rel="stylesheet" href="app/public/css/confirm.css">

</head>
<body>
    <div class="product-details">
        <h1>Confirm Your Purchase</h1>
        <img src="<?= htmlspecialchars($shoe['image']); ?>" alt="<?= htmlspecialchars($shoe['name']); ?>">
        <h2><?= htmlspecialchars($shoe['name']); ?></h2>
        <p>Price per item: $<span id="price"><?= number_format($shoe['price'], 2); ?></span></p>

        <form action="/project/checkout" method="POST">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
            <input type="hidden" name="shoe_id" value="<?= $shoe['id']; ?>">
            
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?= $shoe['stock']; ?>" required>
            
            <p>Total Price: $<span id="total-price"><?= number_format($shoe['price'], 2); ?></span></p>
            
            <button type="submit">Confirm Purchase</button>
        </form>
    </div>

    <script>
        const pricePerItem = <?= $shoe['price']; ?>;
        const quantityInput = document.getElementById('quantity');
        const totalPriceSpan = document.getElementById('total-price');

        quantityInput.addEventListener('input', function() {
            const quantity = parseInt(this.value) || 1;
            const totalPrice = (pricePerItem * quantity).toFixed(2);
            totalPriceSpan.textContent = totalPrice;
        });
    </script>
</body>
</html>
<?php else: ?>
    <p>Shoe not found.</p>
<?php endif; ?>
