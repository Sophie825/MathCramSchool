<?php
include_once '../db/db.php'; 
include '../templates/header.php'; 

// 處理新增或修改教師的表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['teacher_id']) && !isset($_POST['update_teacher'])) {
            echo "<p>新增教師表單已提交</p>";

            // 驗證表單值是否完整
            if (empty($_POST['teacher_id']) || empty($_POST['teacher_name']) || empty($_POST['status'])) {
                echo "<p>所有必要欄位必須填寫</p>";
                return;
            }

            $teacher_id = $_POST['teacher_id'];
            $teacher_name = $_POST['teacher_name'];
            $tel = $_POST['tel'] ?? null;
            $address = $_POST['address'] ?? null;
            $status = $_POST['status'];

            // 檢查是否已存在
            $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM TEACHER WHERE teacher_id = ?");
            $checkStmt->execute([$teacher_id]);
            if ($checkStmt->fetchColumn() > 0) {
                echo "<p>教師 ID 已存在</p>";
                return;
            }

            // 執行新增
            $stmt = $pdo->prepare("INSERT INTO TEACHER (teacher_id, teacher_name, tel, address, status)
                                   VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$teacher_id, $teacher_name, $tel, $address, $status]);

            echo "<p>教師新增成功</p>";
        }
    } catch (PDOException $e) {
        echo "<p>資料庫錯誤：{$e->getMessage()}</p>";
    }
}


// 查詢教師資料
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM TEACHER WHERE teacher_name LIKE ? OR teacher_id LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM TEACHER");
}

$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Math - 教師資料</title>
    <link rel="stylesheet" href="../css/teacher.css">
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
                <li><a href="classroom.php">班級資料</a></li>
                <li><a href="audit.php">試聽資料</a></li>
            </ul>
        </div>
        
        <main class="content">
            <div class="content-header">
                <h1>教師資料</h1>
                <div class="search-bar">
                    <form method="get">
                        <input type="text" name="search" placeholder="搜尋教師資料" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button class="search-button" type="submit">搜尋</button>
                    </form>
                </div>
                <button class="create-button" onclick="document.getElementById('teacher-form').style.display='block'">+ CREATE</button>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>TEL</th>
                        <th>ADDRESS</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($teachers)): ?>
                        <?php foreach ($teachers as $teacher): ?>
                            <tr>
                                <td><?= htmlspecialchars($teacher['teacher_id']) ?></td>
                                <td><?= htmlspecialchars($teacher['teacher_name']) ?></td>
                                <td><?= htmlspecialchars($teacher['tel']) ?></td>
                                <td><?= htmlspecialchars($teacher['address']) ?></td>
                                <td><?= htmlspecialchars($teacher['status']) ?></td>
                                <td>
                                    <button onclick="editTeacher('<?= $teacher['teacher_id'] ?>', '<?= $teacher['teacher_name'] ?>', '<?= $teacher['tel'] ?>', '<?= $teacher['address'] ?>', '<?= $teacher['status'] ?>')">修改</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6">無相關資料</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- 新增或修改教師的表單 -->
            <form id="teacher-form" method="post" style="display:none;">
                <h2>新增/修改教師資料</h2>
                <label>ID：<input type="text" name="teacher_id" id="teacher_id" required></label><br>
                <label>姓名：<input type="text" name="teacher_name" id="teacher_name" required></label><br>
                <label>電話：<input type="text" name="tel" id="tel"></label><br>
                <label>地址：<input type="text" name="address" id="address"></label><br>
                <label>狀態：<input type="text" name="status" id="status"></label><br>
                <input type="hidden" name="update_teacher" id="update_teacher" value="">
                <button type="submit">提交</button>
            </form>
        </main>
        <footer>
            © 2024 Best Math. All rights reserved.
        </footer>
    </div>

    <script>
        // 用於填充修改表單的資料
        function editTeacher(id, name, tel, address, status) {
            document.getElementById('teacher-form').style.display = 'block';
            document.getElementById('teacher_id').value = id;
            document.getElementById('teacher_name').value = name;
            document.getElementById('tel').value = tel;
            document.getElementById('address').value = address;
            document.getElementById('status').value = status;
            document.getElementById('update_teacher').value = '1';
        }
    </script>
</body>
</html>
