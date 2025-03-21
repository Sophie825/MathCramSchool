<?php
include_once '../db/db.php';
include '../templates/header.php';

$search_query = '';
$students = [];
$student_details = null;

// 查詢學生資料
if (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = trim($_GET['search']);
    $stmt = $pdo->prepare("
        SELECT 
            s.student_id, 
            s.student_name, 
            s.school, 
            s.grade, 
            s.tel, 
            s.address, 
            s.status,
            p.parent_name, 
            p.parent_tel 
        FROM STUDENT s
        LEFT JOIN PARENT p ON s.student_id = p.student_id
        WHERE s.student_name LIKE ? OR s.student_id LIKE ?
    ");
    $stmt->execute(["%$search_query%", "%$search_query%"]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // 預設顯示100筆資料
    $stmt = $pdo->query("
        SELECT 
            s.student_id, 
            s.student_name, 
            s.school, 
            s.grade, 
            s.tel, 
            s.address, 
            s.status,
            p.parent_name, 
            p.parent_tel 
        FROM STUDENT s
        LEFT JOIN PARENT p ON s.student_id = p.student_id
        LIMIT 100
    ");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 點選學生後載入該學生詳細資料
if (isset($_GET['student_id']) && !empty(trim($_GET['student_id']))) {
    $student_id = trim($_GET['student_id']);
    $stmt = $pdo->prepare("
        SELECT 
            s.student_id, 
            s.student_name, 
            s.school, 
            s.grade, 
            s.tel, 
            s.address, 
            s.status, 
            s.version,
            p.parent_name, 
            p.parent_tel 
        FROM STUDENT s
        LEFT JOIN PARENT p ON s.student_id = p.student_id
        WHERE s.student_id = ?
    ");
    $stmt->execute([$student_id]);
    $student_details = $stmt->fetch(PDO::FETCH_ASSOC);
}


// 處理表單提交（新增或修改）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? null;

    $student_id = $_POST['student_id'] ?? null;
    $student_name = $_POST['student_name'];
    $school = $_POST['school'];
    $grade = $_POST['grade'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];
    $status = $_POST['status'];
    $parent_name = $_POST['parent_name'];
    $parent_tel = $_POST['parent_tel'];
    $version = $_POST['version'] ?? 0;

    if ($action === 'create') {
        // 檢查 student_id 是否已存在
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM STUDENT WHERE student_id = ?");
        $stmt->execute([$student_id]);
        if ($stmt->fetchColumn() > 0) {
            // 如果 student_id 已存在，提示錯誤並停止執行
            echo "<p>學生 ID 已存在，請使用不同的 ID。</p>";
            return; // 停止執行後續操作
        }
    
        // 新增學生及家長資料
        $stmt = $pdo->prepare("
            INSERT INTO STUDENT (student_id, student_name, school, grade, tel, address, status, version)
            VALUES (?, ?, ?, ?, ?, ?, ?, 0)
        ");
        $stmt->execute([$student_id, $student_name, $school, $grade, $tel, $address, $status]);
    
        $stmt = $pdo->prepare("
            INSERT INTO PARENT (parent_name, parent_tel, student_id)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$parent_name, $parent_tel, $student_id]);
    
        echo "<p>學生新增成功！</p>";
    
    
    } elseif ($action === 'update') {
        // 修改學生及家長資料
        $checkStmt = $pdo->prepare("SELECT version FROM STUDENT WHERE student_id = ?");
        $checkStmt->execute([$student_id]);
        $currentVersion = $checkStmt->fetchColumn();

        if ($currentVersion == $version) {
            $stmt = $pdo->prepare("
                UPDATE STUDENT 
                SET student_name = ?, school = ?, grade = ?, tel = ?, address = ?, status = ?, version = version + 1
                WHERE student_id = ?
            ");
            $stmt->execute([$student_name, $school, $grade, $tel, $address, $status, $student_id]);

            $stmt = $pdo->prepare("
                INSERT INTO PARENT (parent_name, parent_tel, student_id)
                VALUES (?, ?, ?)
                ON CONFLICT (student_id) DO UPDATE 
                SET parent_name = EXCLUDED.parent_name, parent_tel = EXCLUDED.parent_tel
            ");
            $stmt->execute([$parent_name, $parent_tel, $student_id]);

            // echo "<p>學生資料修改成功！</p>";
        } else {
            echo "<p>更新失敗，資料已被其他操作修改，請重新載入頁面。</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Math - 學生資料</title>
    <link rel="stylesheet" href="../css/student.css">
</head>
<body>
    <div class="container">
        <header class="header">
            <div class="logo"><a href="web.php">MATH SCHOOL</a></div>
            <button class="login-button"><a href="web.php">登出</a></button>
        </header>
        <div class="sidebar">
            <ul class="menu">
                <li><a href="student.php">學生資料</a></li>
                <li><a href="teacher.php">教師資料</a></li>
                <li><a href="mentor.php">輔導教師資料</a></li>
                <li><a href="class.php">班級資料</a></li>
                <li><a href="audit.php">試聽資料</a></li>
            </ul>
        </div>
        
        <main class="content">
            <div class="content-header">
                <h1>學生資料</h1>
                <div class="search-bar">
                    <form method="get">
                        <input type="text" name="search" placeholder="搜尋學生資料" value="<?= htmlspecialchars($search_query) ?>">
                        <button class="search-button" type="submit">搜尋</button>
                    </form>
                    <button class="create-button" onclick="document.getElementById('create-form').style.display='block'">+ CREATE</button>
                </div>
            </div>

            <!-- 搜尋結果 -->
            <?php if (!empty($students)): ?>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NAME</th>
                            <th>SCHOOL</th>
                            <th>GRADE</th>
                            <th>PARENT</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['student_id']) ?></td>
                                <td><?= htmlspecialchars($student['student_name']) ?></td>
                                <td><?= htmlspecialchars($student['school'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($student['grade'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($student['parent_name'] ?? 'N/A') ?></td>
                                <td>
                                    <a href="student.php?student_id=<?= htmlspecialchars($student['student_id']) ?>">編輯</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php elseif (!empty($search_query)): ?>
                <p>找不到相關資料。</p>
            <?php endif; ?>

            <!-- 編輯表單 -->
            <?php if ($student_details): ?>
                <form method="post">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="version" value="<?= htmlspecialchars($student_details['version']) ?>">
                    <input type="hidden" name="student_id" value="<?= htmlspecialchars($student_details['student_id']) ?>">
                    <label>ID:<input type="text" name="student_id+ value="<?= htmkspecialchars($student_details['student_id']) ?>: required><label><br>
                    <label>姓名：<input type="text" name="student_name" value="<?= htmlspecialchars($student_details['student_name']) ?>" required></label><br>
                    <label>學校：<input type="text" name="school" value="<?= htmlspecialchars($student_details['school']) ?>"></label><br>
                    <label>年級：<input type="number" name="grade" value="<?= htmlspecialchars($student_details['grade']) ?>"></label><br>
                    <label>電話：<input type="text" name="tel" value="<?= htmlspecialchars($student_details['tel']) ?>"></label><br>
                    <label>地址：<input type="text" name="address" value="<?= htmlspecialchars($student_details['address']) ?>"></label><br>
                    <label>狀態：<input type="text" name="status" value="<?= htmlspecialchars($student_details['status']) ?>"></label><br>
                    <label>家長姓名：<input type="text" name="parent_name" value="<?= htmlspecialchars($student_details['parent_name']) ?>"></label><br>
                    <label>家長電話：<input type="text" name="parent_tel" value="<?= htmlspecialchars($student_details['parent_tel']) ?>"></label><br>
                    <button type="submit">提交</button>
                </form>
            <?php endif; ?>

            <!-- 新增表單 -->
            <form id="create-form" method="post" style="display:none;">
                <input type="hidden" name="action" value="create">
                <label>ID:<input type="text" name="student_id" required></label><br>
                <label>姓名：<input type="text" name="student_name" required></label><br>
                <label>學校：<input type="text" name="school"></label><br>
                <label>年級：<input type="number" name="grade"></label><br>
                <label>電話：<input type="text" name="tel"></label><br>
                <label>地址：<input type="text" name="address"></label><br>
                <label>狀態：<input type="text" name="status"></label><br>
                <label>家長姓名：<input type="text" name="parent_name"></label><br>
                <label>家長電話：<input type="text" name="parent_tel"></label><br>
                <button type="submit">新增</button>
            </form>
        </main>
        <!-- 頁尾 -->
        <footer>
            © 2024 Math School. All rights reserved.
        </footer>
    </div>
</body>
</html>