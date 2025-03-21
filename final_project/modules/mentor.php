<?php
include_once '../db/db.php'; 
include '../templates/header.php'; 

// 處理新增或修改輔導教師的表單提交
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mentor_id']) && !isset($_POST['update_mentor'])) {
        // 新增輔導教師
        $mentor_id = $_POST['mentor_id'];
        $mentor_name = $_POST['mentor_name'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO MENTOR (mentor_id, mentor_name, tel, address, status)
                               VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$mentor_id, $mentor_name, $tel, $address, $status]);
        // echo "<p>輔導教師新增成功！</p>";
    } elseif (isset($_POST['update_mentor'])) {
        // 修改輔導教師
        $mentor_id = $_POST['mentor_id'];
        $mentor_name = $_POST['mentor_name'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE MENTOR 
                               SET mentor_name = ?, tel = ?, address = ?, status = ? 
                               WHERE mentor_id = ?");
        $stmt->execute([$mentor_name, $tel, $address, $status, $mentor_id]);
        // echo "<p>輔導教師修改成功！</p>";
    }
}

// 查詢輔導教師資料，僅顯示 status = 'on'
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("
        SELECT 
            m.mentor_id, 
            m.mentor_name, 
            m.tel, 
            m.address, 
            m.status, 
            ms.shift 
        FROM MENTOR m
        LEFT JOIN MENTOR_SHIFT ms ON m.mentor_id = ms.mentor_id
        WHERE m.status = 'on' AND (m.mentor_name LIKE ? OR m.mentor_id LIKE ?)
    ");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("
        SELECT 
            m.mentor_id, 
            m.mentor_name, 
            m.tel, 
            m.address, 
            m.status, 
            ms.shift 
        FROM MENTOR m
        LEFT JOIN MENTOR_SHIFT ms ON m.mentor_id = ms.mentor_id
        WHERE m.status = 'on'
    ");
}

$mentors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Math - 輔導教師資料</title>
    <link rel="stylesheet" href="../css/mentor.css">
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
                <h1>輔導教師資料</h1>
                <div class="search-bar">
                    <form method="get">
                        <input type="text" name="search" placeholder="搜尋輔導教師資料" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button class="search-button" type="submit">搜尋</button>
                    </form>
                </div>
                <button class="create-button" onclick="document.getElementById('mentor-form').style.display='block'">+ CREATE</button>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>TEL</th>
                        <th>ADDRESS</th>
                        <th>STATUS</th>
                        <th>SHIFT</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($mentors)): ?>
                        <?php foreach ($mentors as $mentor): ?>
                            <tr>
                                <td><?= htmlspecialchars($mentor['mentor_id']) ?></td>
                                <td><?= htmlspecialchars($mentor['mentor_name']) ?></td>
                                <td><?= htmlspecialchars($mentor['tel']) ?></td>
                                <td><?= htmlspecialchars($mentor['address']) ?></td>
                                <td><?= htmlspecialchars($mentor['status']) ?></td>
                                <td><?= htmlspecialchars($mentor['shift'] ?? 'N/A') ?></td>
                                <td>
                                    <button onclick="editMentor('<?= $mentor['mentor_id'] ?>', '<?= $mentor['mentor_name'] ?>', '<?= $mentor['tel'] ?>', '<?= $mentor['address'] ?>', '<?= $mentor['status'] ?>')">修改</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7">無相關資料</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- 新增或修改輔導教師的表單 -->
            <form id="mentor-form" method="post" style="display:none;">
                <h2>新增/修改輔導教師資料</h2>
                <label>ID：<input type="number" name="mentor_id" id="mentor_id" required></label><br>
                <label>姓名：<input type="text" name="mentor_name" id="mentor_name" required></label><br>
                <label>電話：<input type="text" name="tel" id="tel"></label><br>
                <label>地址：<input type="text" name="address" id="address"></label><br>
                <label>狀態：<input type="text" name="status" id="status"></label><br>
                <input type="hidden" name="update_mentor" id="update_mentor" value="">
                <button type="submit">提交</button>
            </form>
        </main>
        <!-- 頁尾 -->
        <footer>
            © 2024 Math School. All rights reserved.
        </footer>
    </div>

    <script>
        // 用於填充修改表單的資料
        function editMentor(id, name, tel, address, status) {
            document.getElementById('mentor-form').style.display = 'block';
            document.getElementById('mentor_id').value = id;
            document.getElementById('mentor_name').value = name;
            document.getElementById('tel').value = tel;
            document.getElementById('address').value = address;
            document.getElementById('status').value = status;
            document.getElementById('update_mentor').value = '1';
        }
    </script>
</body>
</html>
