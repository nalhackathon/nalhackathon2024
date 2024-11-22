<?php
require_once 'db.php';

session_start();
$room_id = $_SESSION['game_id'] ?? '';

// POSTリクエストからルームstateを取得
$player = $_POST['player_id'];

// エラーチェック
if (empty($player)) {
    echo "<script>alert('ユーザー名が設定されていません。'); window.location.href = 'index.php';</script>";
    exit;
}
if (empty($room_id)) {
    echo "<script>alert('ルームが設定されていません。'); window.location.href = 'index.php';</script>";
    exit;
}

try {
    // `room`テーブルで指定されたルームIDの存在を確認
    $stmt = $db->prepare("SELECT roomID, participant FROM room WHERE roomID = :room_id");
    $stmt->execute([':room_id' => $room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) {
        // ルームを削除
        $stmt = $db->prepare("DELETE FROM room WHERE roomID = :room_id");
        $stmt->execute([':room_id' => $room_id]);

        // ルーム内のプレイヤー削除
        $stmt = $db->prepare("DELETE FROM players WHERE game_id = :game_id");
        $stmt->execute([':game_id' => $room_id]);

        $_SESSION['game_id'] = null;

        echo "<script>
            alert('ルームを削除しました。ルームID: {$room_id}');
            window.location.href = 'lobby.php';
        </script>";
    } else {
        echo "<script>alert('削除に失敗しました。'); window.location.href = 'game.php';</script>";
    }
} catch (PDOException $e) {
    echo "エラーが発生しました: " . $e->getMessage();
}

