<?php
header('Content-Type: application/json');

try {
    $db = new PDO('mysql:dbname=hackathon;host=127.0.0.1;charset=utf8mb4', 'naladmin', 'naladmin');
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'データベース接続エラー: ' . $e->getMessage()]);
    exit();
}

// ルーム一覧を取得
$stmt = $db->query("SELECT roomID FROM room");
$rooms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// JSONで返す
echo json_encode(['rooms' => $rooms]);
