<?php
// Khởi tạo các biến lưu trữ dữ liệu và thông báo lỗi
$name = "";
$attendance = "";
$midterm = "";
$final = "";
$error = "";
$result = null;

// Hàm kiểm tra điểm có nằm trong khoảng từ 0 đến 10 hay không
function isValidScore($score) {
    return is_numeric($score) && $score >= 0 && $score <= 10;
}

// Hàm tính điểm tổng kết (10% chuyên cần, 30% giữa kỳ, 60% cuối kỳ)
function finalScore($attendance, $midterm, $final) {
    return ($attendance * 0.1) + ($midterm * 0.3) + ($final * 0.6);
}

// Hàm xếp loại học lực
function getRanking($total) {
    if ($total >= 8.5) return "Giỏi";
    if ($total >= 7.0) return "Khá";
    if ($total >= 5.0) return "Trung bình";
    return "Chưa đạt";
}

// Xử lý khi người dùng nhấn nút Gửi (POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Giữ lại dữ liệu đã nhập
    $name = trim($_POST['name'] ?? '');
    $attendance = $_POST['attendance'] ?? '';
    $midterm = $_POST['midterm'] ?? '';
    $final = $_POST['final'] ?? '';

    // Kiểm tra dữ liệu đầu vào
    if ($name === "" || $attendance === "" || $midterm === "" || $final === "") {
        $error = "Vui lòng nhập đầy đủ tất cả các trường thông tin!";
    } elseif (!isValidScore($attendance) || !isValidScore($midterm) || !isValidScore($final)) {
        // Hiển thị lỗi nếu điểm không nằm trong khoảng 0-10
        $error = "Lỗi: Điểm chuyên cần, giữa kỳ và cuối kỳ phải là số từ 0 đến 10!";
    } else {
        // Tính toán nếu dữ liệu hợp lệ
        $total = finalScore($attendance, $midterm, $final);
        $ranking = getRanking($total);
        
        $result = [
            'name' => htmlspecialchars($name),
            'total' => round($total, 2),
            'ranking' => $ranking
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Tính Điểm Tổng Kết Sinh Viên</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background-color: #f4f4f9; }
        .container { max-width: 500px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        button { background: #28a745; color: white; border: none; padding: 10px 15px; cursor: pointer; border-radius: 4px; font-size: 16px; }
        button:hover { background: #218838; }
        .error { color: red; margin-bottom: 15px; font-weight: bold; }
        .result { margin-top: 20px; background: #e9ecef; padding: 15px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Nhập Thông Tin Điểm Sinh Viên</h2>

    <!-- Hiển thị thông báo lỗi nếu có -->
    <?php if (!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Form sử dụng phương thức POST[cite: 1] -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Họ và tên:</label>
            <!-- Giữ lại dữ liệu đã nhập (value) khi có lỗi[cite: 1] -->
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
        </div>

        <div class="form-group">
            <label for="attendance">Điểm chuyên cần (0 - 10):</label>
            <input type="number" step="0.1" id="attendance" name="attendance" value="<?php echo htmlspecialchars($attendance); ?>" required>
        </div>

        <div class="form-group">
            <label for="midterm">Điểm giữa kỳ (0 - 10):</label>
            <input type="number" step="0.1" id="midterm" name="midterm" value="<?php echo htmlspecialchars($midterm); ?>" required>
        </div>

        <div class="form-group">
            <label for="final">Điểm cuối kỳ (0 - 10):</label>
            <input type="number" step="0.1" id="final" name="final" value="<?php echo htmlspecialchars($final); ?>" required>
        </div>

        <button type="submit">Tính Điểm</button>
    </form>

    <!-- Hiển thị kết quả sau khi tính toán thành công -->
    <?php if ($result): ?>
        <div class="result">
            <h3>Kết Quả Tổng Kết</h3>
            <p><strong>Họ tên:</strong> <?php echo $result['name']; ?></p>
            <p><strong>Điểm tổng kết:</strong> <?php echo $result['total']; ?></p>
            <p><strong>Xếp loại:</strong> <?php echo $result['ranking']; ?></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>