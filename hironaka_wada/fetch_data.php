<?php
header('Content-Type: application/json');

// データベース接続情報
$host = '127.0.0.1';
$dbname = 'hackathon';
$username = 'naladmin';
$password = 'naladmin';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
} catch (PDOException $e) {
    echo json_encode(["error" => "データベース接続エラー: " . $e->getMessage()]);
    exit();
}

// データを取得
$sql = "SELECT username, card, discard, reason FROM test";
$stmt = $db->query($sql);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($data);
?>
