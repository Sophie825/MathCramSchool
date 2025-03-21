<?php
session_start();
include 'templates/header.php';

// 確保用戶已登入
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

// 根據身份顯示不同功能
$role = $_SESSION['role'];
$username = $_SESSION['username'];

echo "<h1>歡迎, $username！</h1>";
echo "<h2>您的身份是：$role</h2>";

if ($role === 'admin') {
    echo "<ul>
        <li><a href='modules/student.php'>學生管理</a></li>
        <li><a href='modules/teacher.php'>教師管理</a></li>
        <li><a href='modules/mentor.php'>Mentor 管理</a></li>
        <li><a href='modules/parent.php'>家長管理</a></li>
        <li><a href='modules/reservation.php'>預約管理</a></li>
        <li><a href='modules/classroom.php'>教室管理</a></li>
        <li><a href='modules/student_schedule.php'>學生課表管理</a></li>
        <li><a href='modules/teacher_schedule.php'>教師課表管理</a></li>
    </ul>";
} elseif ($role === 'student') {
    echo "<ul>
        <li><a href='modules/reservation.php'>新增預約</a></li>
        <li><a href='modules/reservation.php'>查詢預約</a></li>
    </ul>";
} elseif ($role === 'mentor') {
    echo "<ul>
        <li><a href='modules/reservation.php'>查詢預約</a></li>
    </ul>";
} else {
    echo "<p>您沒有權限訪問此系統。</p>";
}
echo "<a href='logout.php'>登出</a>";

include 'templates/footer.php';
?>