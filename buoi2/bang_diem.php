<?php
// 1. Tạo mảng `$students` gồm 3 sinh viên với name, midterm, final
$students = [
    [
        'name' => 'Phan Công Thành',
        'midterm' => 7.5,
        'final' => 8.0
    ],
    [
        'name' => 'Trần Hoài An',
        'midterm' => 4.0,
        'final' => 5.0
    ],
    [
        'name' => 'Nguyễn Thị Nở',
        'midterm' => 3.5,
        'final' => 4.0
    ]
];

// 2. Viết hàm `calculateAverage()` tính điểm trung bình
function calculateAverage($midterm,$final) {
    return ($midterm +$final) / 2;
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Bảng điểm sinh viên</title>
    <style>
        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }
        th, td {
            border: 1px solid #cccccc;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .pass {
            color: green;
            font-weight: bold;
        }
        .fail {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>

    <h2 style="text-align: center;">BẢNG ĐIỂM SINH VIÊN</h2>

    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sinh viên</th>
                <th>Điểm giữa kỳ</th>
                <th>Điểm cuối kỳ</th>
                <th>Điểm trung bình</th>
                <th>Kết quả</th> <!-- 4. Cột kết quả[cite: 2] -->
            </tr>
        </thead>
        <tbody>
            <!-- 3. Dùng `foreach` để in bảng HTML[cite: 2] -->
            <?php foreach ($students as $index =>$student): ?>
                <?php 
                    // Tính điểm trung bình bằng hàm calculateAverage
                    $avg = calculateAverage($student['midterm'],$student['final']);
                    
                    // 4. Kiểm tra kết quả: Đạt nếu điểm trung bình >= 5
                    $isPassed =$avg >= 5;
                    $status =$isPassed ? 'Đạt' : 'Chưa đạt';
                    $statusClass =$isPassed ? 'pass' : 'fail';

                    // 5. Mã hóa tên sinh viên bằng `htmlspecialchars()` trước khi in
                    $safeName = htmlspecialchars($student['name'], ENT_QUOTES, 'UTF-8');
                ?>
                <tr>
                    <td><?php echo $index + 1; ?></td>
                    <td><?php echo $safeName; ?></td>
                    <td><?php echo $student['midterm']; ?></td>
                    <td><?php echo $student['final']; ?></td>
                    <td><?php echo number_format($avg, 2); ?></td>
                    <td class="<?php echo $statusClass; ?>"><?php echo $status; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>