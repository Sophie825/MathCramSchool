<?php
session_start();
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>輔導老師主頁</title>
    <link rel="stylesheet" href="css/student_dashboard.css">
</head>
<body>
    <div class="container">
        <!-- 頁面頂部 -->
        <header class="header">
            <div class="logo"><a href="web.php">MATH SCHOOL</a></div>
            <!-- <button class="login-button"><a href="logout.php">登出</a></button> -->
        </header>

        <!-- 頁面內容 -->
        <main class="main-content">
            <h1>歡迎，輔導老師專區</h1>
            <p>請選擇您需要的功能：</p>
            
            <div class="options">
                <div class="option-card">
                    <h3>查看輔導老師班表</h3>
                    <button onclick="window.location.href='modules/mentor_shift.php'">進入</button>
                </div>
            </div>
        </main>

        <!-- 頁尾 -->
        <footer>
            © 2024 Math School. All rights reserved.
        </footer>
    
    </div>
</body>
</html>