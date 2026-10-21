
<?php
function e(string $value): string {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

$name = '';
$email = '';
$subject = '';
$message = '';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Kiểm tra họ tên
    if ($name === '') {
        $errors['name'] = 'Vui lòng nhập họ tên.';
    } elseif (strlen($name) > 100) {
        $errors['name'] = 'Họ tên không được vượt quá 100 ký tự.';
    }

    // Kiểm tra email
    if ($email === '') {
        $errors['email'] = 'Vui lòng nhập email.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email không đúng định dạng.';
    }

    // Chỉ chấp nhận các chủ đề được quy định
    $allowedSubjects = [
        'Hỗ trợ kỹ thuật',
        'Tư vấn',
        'Góp ý'
    ];

    if (!in_array($subject, $allowedSubjects, true)) {
        $errors['subject'] = 'Vui lòng chọn chủ đề hợp lệ.';
    }

    // Kiểm tra nội dung
    if ($message === '') {
        $errors['message'] = 'Vui lòng nhập nội dung liên hệ.';
    } elseif (strlen($message) < 10 || strlen($message) > 1000) {
        $errors['message'] = 'Nội dung phải từ 10 đến 1000 ký tự.';
    }

    // Kiểm tra ảnh tải lên
    if (
        isset($_FILES['avatar']) &&
        $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE
    ) {
        $file = $_FILES['avatar'];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errors['avatar'] = 'Không thể tải ảnh lên.';
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $errors['avatar'] = 'Ảnh không được vượt quá 2 MB.';
        } else {
            $imageInfo = getimagesize($file['tmp_name']);

            if ($imageInfo === false) {
                $errors['avatar'] = 'File tải lên không phải ảnh hợp lệ.';
            } elseif (!in_array($imageInfo['mime'], [
                'image/jpeg',
                'image/png',
                'image/webp'
            ], true)) {
                $errors['avatar'] = 'Chỉ chấp nhận ảnh JPG, PNG hoặc WEBP.';
            }
        }
    }

    // Chỉ báo thành công khi không có lỗi
    if (empty($errors)) {
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Form liên hệ</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f1f5f9;
            margin: 0;
            padding: 30px 15px;
            color: #263445;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px #00000012;
        }

        h2 {
            text-align: center;
            color: #1769aa;
            margin-top: 0;
        }

        label {
            display: block;
            margin: 17px 0 7px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 11px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font: inherit;
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        button {
            width: 100%;
            margin-top: 22px;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #2474b5;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #185b91;
        }

        .error {
            color: #d93025;
            margin: 6px 0 0;
            font-size: 14px;
        }

        .success {
            background: #e4f7e9;
            color: #176b35;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .note {
            color: #64748b;
            font-size: 13px;
        }
    </style>
</head>

<body>
<div class="container">
    <h2>FORM LIÊN HỆ</h2>
    <p class="note">Vui lòng điền thông tin để gửi liên hệ.</p>

    <?php if ($success): ?>
        <div class="success">
            <strong>Gửi thông tin thành công!</strong>
            <p>Họ tên: <?= e($name) ?></p>
            <p>Email: <?= e($email) ?></p>
            <p>Chủ đề: <?= e($subject) ?></p>
            <p>Nội dung: <?= nl2br(e($message)) ?></p>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="name">Họ tên</label>
        <input
            type="text"
            id="name"
            name="name"
            maxlength="100"
            value="<?= e($name) ?>"
            required
        >
        <?php if (isset($errors['name'])): ?>
            <p class="error"><?= e($errors['name']) ?></p>
        <?php endif; ?>

        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= e($email) ?>"
            required
        >
        <?php if (isset($errors['email'])): ?>
            <p class="error"><?= e($errors['email']) ?></p>
        <?php endif; ?>

        <label for="subject">Chủ đề</label>
        <select id="subject" name="subject" required>
            <option value="">-- Chọn chủ đề --</option>
            <option value="Hỗ trợ kỹ thuật"
                <?= $subject === 'Hỗ trợ kỹ thuật' ? 'selected' : '' ?>>
                Hỗ trợ kỹ thuật
            </option>
            <option value="Tư vấn"
                <?= $subject === 'Tư vấn' ? 'selected' : '' ?>>
                Tư vấn
            </option>
            <option value="Góp ý"
                <?= $subject === 'Góp ý' ? 'selected' : '' ?>>
                Góp ý
            </option>
        </select>
        <?php if (isset($errors['subject'])): ?>
            <p class="error"><?= e($errors['subject']) ?></p>
        <?php endif; ?>

        <label for="message">Nội dung liên hệ</label>
        <textarea
            id="message"
            name="message"
            minlength="10"
            maxlength="1000"
            required
        ><?= e($message) ?></textarea>
        <?php if (isset($errors['message'])): ?>
            <p class="error"><?= e($errors['message']) ?></p>
        <?php endif; ?>

        <label for="avatar">Ảnh đại diện (không bắt buộc)</label>
        <input
            type="file"
            id="avatar"
            name="avatar"
            accept=".jpg,.jpeg,.png,.webp"
        >
        <p class="note">Chỉ nhận JPG, PNG, WEBP; dung lượng tối đa 2 MB.</p>
        <?php if (isset($errors['avatar'])): ?>
            <p class="error"><?= e($errors['avatar']) ?></p>
        <?php endif; ?>

        <button type="submit">Gửi liên hệ</button>
    </form>
</div>
</body>
</html>
