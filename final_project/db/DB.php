<?php
// 資料庫連線設定 自行設定
$host = 'localhost';
$dbname = 'math_school';
$user = 'postgres';
$password = '000abc123';

try {
    // 使用 PDO 連接 PostgreSQL 資料庫
    $pdo = new PDO("pgsql:host=$host;dbname=$dbname", $user, $password);
    
    // 設定錯誤模式為拋出異常
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // echo "資料庫連線成功！";

} catch (PDOException $e) {
    // 捕捉連線錯誤並顯示訊息
    die("資料庫連線失敗：" . $e->getMessage());
}

?>
