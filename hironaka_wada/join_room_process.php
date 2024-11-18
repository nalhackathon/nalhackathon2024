<?php
require_once 'db.php';

session_start();
$username = $_SESSION['username'] ?? '';

// エラーチェック
if (empty($username)) {
    echo "<script>alert('ユーザー名が設定されていません。タイトル画面に戻ってください。'); window.location.href = 'index.php';</script>";
    exit;
}

// POSTリクエストからルームIDを取得
$room_id = $_POST['room_id'];

try {
    // `room`テーブルで指定されたルームIDの存在を確認
    $stmt = $db->prepare("SELECT roomID, participant FROM room2 WHERE roomID = :room_id");
    $stmt->execute([':room_id' => $room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) {
        // `players`テーブルに参加者を追加
        $stmt = $db->prepare("INSERT INTO players (game_id, name) VALUES (:game_id, :name)");
        $stmt->execute([':game_id' => $room_id, ':name' => $username]);

        // `room`テーブルの`participant`に参加者を追加（カンマ区切り）
        $updatedParticipants = $room['participant'] 
            ? $room['participant'] . ',' . $username 
            : $username;
        
        $stmt = $db->prepare("UPDATE room SET participant = :participant WHERE roomID = :room_id");
        $stmt->execute([':participant' => $updatedParticipants, ':room_id' => $room_id]);

        // ゲームIDをセッションに保存
        $_SESSION['game_id'] = $room['roomID'];
        $_SESSION['player_id'] = $username;

        echo "<script>
            alert('ルームに参加しました。ゲームID: {$room['roomID']}');
            window.location.href = 'game.php';
        </script>";
    } else {
        echo "<script>alert('ルームが見つかりません。'); window.location.href = 'index.php';</script>";
    }
} catch (PDOException $e) {
    echo "エラーが発生しました: " . $e->getMessage();
}
?>
