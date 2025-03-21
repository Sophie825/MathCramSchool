<?php
include_once '../db/db.php'; 
include '../templates/header.php'; 

// 查詢 MENTOR_SHIFT 和 MENTOR 表的固定班表
$stmt = $pdo->prepare("
    SELECT 
        MENTOR_SHIFT.shift, 
        MENTOR.mentor_name
    FROM MENTOR_SHIFT
    LEFT JOIN MENTOR ON MENTOR_SHIFT.mentor_id = MENTOR.mentor_id
    ORDER BY MENTOR_SHIFT.shift ASC
");
$stmt->execute();

// 將班表按 shift 分組
$shifts = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $shift = $row['shift'];
    $mentor = $row['mentor_name'] ?? '無指定導師';

    // 如果班次尚未初始化，則建立班次組
    if (!isset($shifts[$shift])) {
        $shifts[$shift] = [];
    }

    // 將導師名加入對應班次
    $shifts[$shift][] = $mentor;
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>輔導老師班表</title>
    <link rel="stylesheet" href="../css/style_mentor_shift.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <a href="web.php">MATH SCHOOL</a>
        </div>
    </header>

    <div class="container">
        <main class="content">
            <h1>輔導老師班表</h1>
            <div class="schedule-grid">
                <?php foreach ($shifts as $shift => $mentors): ?>
                    <div class="option-card">
                        <h3>班次：<?= htmlspecialchars($shift) ?></h3>
                        <div class="teachers">
                            <?php foreach ($mentors as $mentor): ?>
                                <button class="mentor_name"><?= htmlspecialchars($mentor) ?></button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </main>
        <!-- 頁尾 -->
        <footer>
            © 2024 Math School. All rights reserved.
        </footer>
    </div>
</body>
</html>
