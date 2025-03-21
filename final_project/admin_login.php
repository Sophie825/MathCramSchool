<?php
session_start();
include_once 'db/db.php'; // 引入資料庫連線

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $worker_id = $_POST['worker_id']; // 假設帳號是 worker_id
    $password = $_POST['password'];

    // 查詢用戶資料
    $stmt = $pdo->prepare("
        SELECT worker_id, password, worker_name, status 
        FROM WORKERS 
        WHERE worker_id = ?
    ");
    $stmt->execute([$worker_id]);
    $user = $stmt->fetch();

    // 驗證密碼（未加密）
    if ($user && $password === $user['password']) {
        // 登入成功，保存會話資訊
        $_SESSION['user_id'] = $user['worker_id'];
        $_SESSION['username'] = $user['worker_name'];
        // $_SESSION['role'] = $user['status']; // 身份：如 "admin", "mentor"

        // 跳轉到管理主頁
        header('Location: admin_main.php');
        exit();
    } else {
        // 登入失敗訊息
        $error_message = "帳號或密碼錯誤！";
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>行政人員登入</title>
    <link rel="stylesheet" href="css/style_admin_login.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo"><a href="web.php">MATH SCHOOL</a></div>
        </header>
        <main class="main-content">
            <h2>行政人員登入</h2>
            <?php if (isset($error_message)): ?>
                <p style="color: red; text-align: center;"><?= htmlspecialchars($error_message) ?></p>
            <?php endif; ?>
            <form action="admin_login.php" method="POST" class="login-form">
                <div class="form-group">
                    <label for="worker_id">帳號：</label>
                    <input type="text" id="worker_id" name="worker_id" placeholder="請輸入帳號" required>
                </div>
                <div class="form-group">
                    <label for="password">密碼：</label>
                    <input type="password" id="password" name="password" placeholder="請輸入密碼" required>
                </div>
                <button type="submit" class="login-button">登入</button>
            </form>
            <p>忘記密碼？請聯繫系統管理員。</p>
        </main>
        <!-- 頁尾 >
        <footer>
            © 2024 Math School. All rights reserved.
        </footer -->
    </div>
</body>
</html>