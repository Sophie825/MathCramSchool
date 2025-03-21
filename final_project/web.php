<?php
?>
<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Math School - 系統入口</title>
    <link rel="stylesheet" href="css/style_welcome_page.css">
</head>
<body>
    <div class="header">
        <h1>MATH SCHOOL</h1>
    </div>
    <div class="welcome-section">
        <h2>歡迎來到 Math School</h2>
        <p>請選擇您的身份以進入系統</p>
        <div class="card-container">
            <div class="card">
                <h3>學生入口</h3>
                <button onclick="window.location.href='student_dashboard.php'">進入</button>
            </div>
            <div class="card">
                <h3>輔導老師入口</h3>
                <button onclick="window.location.href='mentor_dashboard.php'">進入</button>
            </div>
            <div class="card">
                <h3>行政人員登入</h3>
                <button onclick="window.location.href='admin_login.php'">登入</button>
            </div>
        </div>
        <footer>
            © 2024 Math School. All rights reserved.
        </footer>
    </div>

</body>
</html>
