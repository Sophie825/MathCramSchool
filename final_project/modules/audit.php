<?php
include_once '../db/db.php'; 
include '../templates/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 新增審核記錄
    if (isset($_POST['audit_id']) && !isset($_POST['update_audit'])) {
        $audit_id = $_POST['audit_id'];
        $audit_name = $_POST['audit_name'];
        $school = $_POST['school'];
        $grade = $_POST['grade'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("INSERT INTO AUDIT (audit_id, audit_name, school, grade, tel, address, status)
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$audit_id, $audit_name, $school, $grade, $tel, $address, $status]);
        // echo "<p>試聽記錄新增成功！</p>";
    }

    // 修改審核記錄
    if (isset($_POST['update_audit'])) {
        $audit_id = $_POST['audit_id'];
        $audit_name = $_POST['audit_name'];
        $school = $_POST['school'];
        $grade = $_POST['grade'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $status = $_POST['status'];

        $stmt = $pdo->prepare("UPDATE AUDIT 
                               SET audit_name = ?, school = ?, grade = ?, tel = ?, address = ?, status = ? 
                               WHERE audit_id = ?");
        $stmt->execute([$audit_name, $school, $grade, $tel, $address, $status, $audit_id]);
        //echo "<p>試聽記錄修改成功！</p>";
    }
}

// 查詢功能
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $stmt = $pdo->prepare("SELECT * FROM AUDIT WHERE audit_name LIKE ? OR audit_id LIKE ?");
    $stmt->execute(["%$search%", "%$search%"]);
} else {
    $stmt = $pdo->query("SELECT * FROM AUDIT");
}

$audit_records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Math - 試聽資料</title>
    <link rel="stylesheet" href="../css/audit.css">
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
                <h1>試聽資料</h1>
                <div class="search-bar">
                    <form method="get">
                        <input type="text" name="search" placeholder="搜尋試聽資料" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button class="search-button" type="submit">搜尋</button>
                    </form>
                </div>
                <button class="create-button" onclick="document.getElementById('audit-form').style.display='block'">+ CREATE</button>
            </div>
            
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>SCHOOL</th>
                        <th>GRADE</th>
                        <th>TEL</th>
                        <th>ADDRESS</th>
                        <th>STATUS</th>
                        <th>ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($audit_records)): ?>
                        <?php foreach ($audit_records as $record): ?>
                            <tr>
                                <td><?= htmlspecialchars($record['audit_id']) ?></td>
                                <td><?= htmlspecialchars($record['audit_name']) ?></td>
                                <td><?= htmlspecialchars($record['school']) ?></td>
                                <td><?= htmlspecialchars($record['grade']) ?></td>
                                <td><?= htmlspecialchars($record['tel']) ?></td>
                                <td><?= htmlspecialchars($record['address']) ?></td>
                                <td><?= htmlspecialchars($record['status']) ?></td>
                                <td>
                                    <button onclick="editAudit('<?= $record['audit_id'] ?>', '<?= $record['audit_name'] ?>', '<?= $record['school'] ?>', '<?= $record['grade'] ?>', '<?= $record['tel'] ?>', '<?= $record['address'] ?>', '<?= $record['status'] ?>')">修改</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">無相關資料</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <!-- 新增或修改審核資料的表單 -->
            <form id="audit-form" method="post" style="display:none;">
                <h2>新增/修改試聽資料</h2>
                <label>ID：<input type="text" name="audit_id" id="audit_id" required></label><br>
                <label>姓名：<input type="text" name="audit_name" id="audit_name" required></label><br>
                <label>學校：<input type="text" name="school" id="school"></label><br>
                <label>年級：<input type="text" name="grade" id="grade"></label><br>
                <label>電話：<input type="text" name="tel" id="tel"></label><br>
                <label>地址：<input type="text" name="address" id="address"></label><br>
                <label>狀態：<input type="text" name="status" id="status"></label><br>
                <input type="hidden" name="update_audit" id="update_audit" value="">
                <button type="submit">提交</button>
            </form>
        </main>
    </div>

    <script>
        // 用於填充修改表單的資料
        function editAudit(id, name, school, grade, tel, address, status) {
            document.getElementById('audit-form').style.display = 'block';
            document.getElementById('audit_id').value = id;
            document.getElementById('audit_name').value = name;
            document.getElementById('school').value = school;
            document.getElementById('grade').value = grade;
            document.getElementById('tel').value = tel;
            document.getElementById('address').value = address;
            document.getElementById('status').value = status;
            document.getElementById('update_audit').value = '1';
        }
    </script>
</body>
</html>