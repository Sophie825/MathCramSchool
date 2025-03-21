<?php
include_once '../db/db.php';
include '../templates/header.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['action'] === 'create') {
        // 新增班級
        $class_name = $_POST['name'];
        $time = $_POST['time'];
        $semester = $_POST['semester'];
        $teacher_id = $_POST['teacher'];
        $classroom_id = $_POST['classroom'];

        $stmt = $pdo->prepare("
            INSERT INTO CLASS (class_name, time, semester, classroom_id)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$class_name, $time, $semester, $classroom_id]);

        $class_id = $pdo->lastInsertId();

        $stmt = $pdo->prepare("
            INSERT INTO TEACH (class_id, teacher_id)
            VALUES (?, ?)
        ");
        $stmt->execute([$class_id, $teacher_id]);

        echo json_encode(['status' => 'success', 'message' => '班級新增成功']);
        exit;
    } elseif ($_POST['action'] === 'update') {
        // 修改班級
        $class_id = $_POST['id'];
        $class_name = $_POST['name'];
        $time = $_POST['time'];
        $semester = $_POST['semester'];
        $teacher_id = $_POST['teacher'];
        $classroom_id = $_POST['classroom'];

        $stmt = $pdo->prepare("
            UPDATE CLASS 
            SET class_name = ?, time = ?, semester = ?, classroom_id = ?
            WHERE class_id = ?
        ");
        $stmt->execute([$class_name, $time, $semester, $classroom_id, $class_id]);

        $stmt = $pdo->prepare("
            UPDATE TEACH 
            SET teacher_id = ?
            WHERE class_id = ?
        ");
        $stmt->execute([$teacher_id, $class_id]);

        echo json_encode(['status' => 'success', 'message' => '班級資料更新成功']);
        exit;
    }
}

$search_query = $_GET['search'] ?? '';
$stmt = $pdo->prepare("
    SELECT 
        c.class_id, c.class_name, c.time, c.semester, c.classroom_id, 
        t.teacher_id, tr.teacher_name
    FROM CLASS c
    LEFT JOIN TEACH t ON c.class_id = t.class_id
    LEFT JOIN TEACHER tr ON t.teacher_id = tr.teacher_id
    WHERE c.class_name LIKE ?
");
$stmt->execute(["%$search_query%"]);
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Best Math - 班級資料</title>
    <link rel="stylesheet" href="../css/class.css">
</head>
<body>
<div class="container">
    <header class="header">
        <div class="logo"><a href="web.html">MATH SCHOOL</a></div>
        <button class="login-button"><a href="web.html">登出</a></button>
    </header>

    <div class="sidebar">
        <ul class="menu">
            <li><a href="student.php">學生資料</a></li>
            <li><a href="teacher.php">教師資料</a></li>
            <li><a href="mentor.php">輔導老師資料</a></li>
            <li><a href="edit_mentor_shift.php">輔導老師班表</a></li>
            <li><a href="class.php">班級資料</a></li>
            <li><a href="time_class_classroom.php">班級教室時間表</a></li>
            <li><a href="audit.php">試聽資料</a></li>
        </ul>
    </div>

    <main class="content">
        <div class="content-header">
            <h1>班級資料</h1>
            <div class="search-bar">
                <form method="get">
                    <input type="text" name="search" placeholder="搜尋班級資料" value="<?= htmlspecialchars($search_query) ?>">
                    <button class="search-button" type="submit">搜尋</button>
                </form>
            </div>
            <button id="createButton" class="create-button" onclick="openCreateModal()">+ 新增</button>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>班級名稱</th>
                    <th>上課時間</th>
                    <th>學期</th>
                    <th>授課老師</th>
                    <th>教室</th>
                    <th>查看班級學生</th>
                    <th>編輯</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($classes as $class): ?>
                    <tr>
                        <td><?= htmlspecialchars($class['class_name']) ?></td>
                        <td><?= htmlspecialchars($class['time']) ?></td>
                        <td><?= htmlspecialchars($class['semester']) ?></td>
                        <td><?= htmlspecialchars($class['teacher_name']) ?></td>
                        <td><?= htmlspecialchars($class['classroom_id']) ?></td>
                        <td>
                            <button onclick="location.href='student_list.php?class_id=<?= htmlspecialchars($class['class_id']) ?>'">
                                查看班級學生
                            </button>
                        </td>
                        <td>
                            <button onclick="editClass(
                                <?= $class['class_id'] ?>,
                                '<?= htmlspecialchars($class['class_name']) ?>',
                                '<?= htmlspecialchars($class['time']) ?>',
                                '<?= htmlspecialchars($class['semester']) ?>',
                                '<?= htmlspecialchars($class['teacher_id']) ?>',
                                '<?= htmlspecialchars($class['classroom_id']) ?>'
                            )">編輯</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>

<!-- 其他內容維持不變 -->


<!-- 新增班級彈出框 -->
<div id="createModal" class="modal" style="display:none;">
    <form id="createForm" method="post">
        <h2>新增班級資料</h2>
        <input type="hidden" name="action" value="create">
        <label>班級名稱：<input type="text" name="name" required></label>
        <label>上課時間：<input type="text" name="time" required></label>
        <label>學期：<input type="text" name="semester" required></label>
        <label>授課老師：
            <select name="teacher" required>
                <!-- 這裡填充所有老師的資料 -->
            </select>
        </label>
        <label>教室：
            <select name="classroom" required>
                <!-- 這裡填充所有教室的資料 -->
            </select>
        </label>
        <button type="submit">確認新增</button>
    </form>
</div>

<!-- 編輯班級彈出框 -->
<div id="editModal" class="modal" style="display:none;">
    <form id="editForm" method="post">
        <h2>編輯班級資料</h2>
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" id="editClassId">
        <label>班級名稱：<input type="text" name="name" id="editClassName" required></label>
        <label>上課時間：<input type="text" name="time" id="editClassTime" required></label>
        <label>學期：<input type="text" name="semester" id="editClassSemester" required></label>
        <label>授課老師：
            <select name="teacher" id="editClassTeacher" required>
                <!-- 這裡填充所有老師的資料 -->
            </select>
        </label>
        <label>教室：
            <select name="classroom" id="editClassClassroom" required>
                <!-- 這裡填充所有教室的資料 -->
            </select>
        </label>
        <button type="submit">確認更新</button>
    </form>
</div>


<script>
function openCreateModal() {
    document.getElementById('createModal').style.display = 'block';
}

function editClass(id, name, time, semester, teacher, classroom) {
    document.getElementById('editModal').style.display = 'block';
    document.getElementById('editClassId').value = id;
    document.getElementById('editClassName').value = name;
    document.getElementById('editClassTime').value = time;
    document.getElementById('editClassSemester').value = semester;
    document.getElementById('editClassTeacher').value = teacher;
    document.getElementById('editClassClassroom').value = classroom;
}
</script>
</body>
</html>
