<?php
include_once '../db/db.php'; 
include '../templates/header.php'; 

// 獲取傳遞的 class_id
$class_id = $_GET['class_id'] ?? null;

if (!$class_id) {
    echo "<p>未指定課程編號！</p>";
    exit;
}

// 查詢對應課程的學生名單
$stmt = $pdo->prepare("
    SELECT 
        STUDENT.student_id, 
        STUDENT.student_name, 
        STUDENT.school, 
        STUDENT.grade, 
        STUDENT.tel, 
        STUDENT.address, 
        PARENT.parent_name, 
        PARENT.parent_tel
    FROM TAKE
    INNER JOIN STUDENT ON TAKE.student_id = STUDENT.student_id
    LEFT JOIN PARENT ON STUDENT.student_id = PARENT.student_id
    WHERE TAKE.class_id = ?
");
$stmt->execute([$class_id]);

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<h2>學生名單</h2>
<head>
    <link rel="stylesheet" href="../css/student_list.css">
<head>
<table border="1">
    <thead>
        <tr>
            <th>ID</th>
            <th>NAME</th>
            <th>SCHOOL</th>
            <th>GRADE</th>
            <th>TEL</th>
            <th>ADDRESS</th>
            <th>PARENT</th>
            <th>PARENT_TEL</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($students)): ?>
            <?php foreach ($students as $student): ?>
                <tr>
                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                    <td><?php echo htmlspecialchars($student['student_name']); ?></td>
                    <td><?php echo htmlspecialchars($student['school']); ?></td>
                    <td><?php echo htmlspecialchars($student['grade']); ?></td>
                    <td><?php echo htmlspecialchars($student['tel']); ?></td>
                    <td><?php echo htmlspecialchars($student['address']); ?></td>
                    <td><?php echo htmlspecialchars($student['parent_name'] ?? '無家長資料'); ?></td>
                    <td><?php echo htmlspecialchars($student['parent_tel'] ?? '無家長電話'); ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">無相關學生資料</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include '../templates/footer.php'; ?>