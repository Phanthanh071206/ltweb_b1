
<?php
function money(float $value): string {
    return number_format($value, 0, ',', '.') . ' đ';
}

$name = $_POST['name'] ?? '';
$qty = (int)($_POST['qty'] ?? 0);
$price = (float)($_POST['price'] ?? 0);

$total = $qty * $price;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tính tiền tài liệu</title>
</head>
<body>
    <h2>TÍNH TIỀN TÀI LIỆU</h2>

    <form method="post">
        <p>
            Tên tài liệu:
            <input type="text" name="name"
                   placeholder="Tên tài liệu" required>
        </p>

        <p>
            Số lượng:
            <input type="number" name="qty" min="1" required>
        </p>

        <p>
            Đơn giá:
            <input type="number" name="price" min="0"
                   step="any" required>
        </p>

        <button type="submit">Tính</button>
    </form>

    <?php if ($total > 0): ?>
        <p>
            Tên tài liệu:
            <?= htmlspecialchars($name, ENT_QUOTES, 'UTF-8') ?>
            <br>
            Số tiền:
            <?= money($total) ?>
        </p>
    <?php endif; ?>
</body>
</html>
