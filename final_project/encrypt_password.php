<?php
include 'db/db.php';

$stmt = $pdo->query("SELECT worker_id, password FROM WORKERS");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($users as $user) {
    $hashedPassword = password_hash($user['password'], PASSWORD_DEFAULT);
    $updateStmt = $pdo->prepare("UPDATE WORKERS SET password = ? WHERE worker_id = ?");
    $updateStmt->execute([$hashedPassword, $user['worker_id']]);
}

echo "所有密碼已成功加密！";
?>