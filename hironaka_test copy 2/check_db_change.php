<?php
// データベース接続情報
$host = '127.0.0.1';
$dbname = 'hackathon';
$username = 'naladmin';
$password = 'naladmin';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['error' => "データベース接続エラー: " . $e->getMessage()]);
    exit();
}

// データベースの最新の更新日時を取得
session_start();
$lastCheckTime = $_SESSION['last_check_time'] ?? null;

try {
    $checkSql = "SELECT MAX(updated_at) AS last_update FROM test";
    $checkStmt = $db->query($checkSql);
    $lastUpdate = $checkStmt->fetchColumn();

    if ($lastCheckTime === null || $lastUpdate > $lastCheckTime) {
        $_SESSION['last_check_time'] = $lastUpdate;
        echo json_encode(['changed' => true]);
    } else {
        echo json_encode(['changed' => false]);
    }
} catch (PDOException $e) {
    echo json_encode(['error' => "エラー: " . $e->getMessage()]);
}
