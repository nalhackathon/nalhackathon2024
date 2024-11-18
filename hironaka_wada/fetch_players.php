<?php
require_once 'db.php';

session_start();
$game_id = $_SESSION['game_id'] ?? null;

if (!$game_id) {
    echo json_encode(['status' => 'error', 'message' => 'ゲームIDが設定されていません。']);
    exit;
}

try {
    // 指定されたゲームのプレイヤーリストを取得
    $stmt = $db->prepare("SELECT name FROM players WHERE game_id = :game_id");
    $stmt->execute([':game_id' => $game_id]);
    $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'players' => $players]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
?>
