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
    $participant = explode(",", $room['participant']);
    // $participantから$playerを削除
    $participant = array_filter($participant, function($value) use ($player) {
        return $value !== $player;
    });
    $participant = array_values($participant);

    if ($room) {

        // `players`テーブルに参加者を削除
        $stmt = $db->prepare("DELETE FROM players WHERE game_id = :game_id AND name = :name");
        $stmt->execute([':game_id' => $room_id, ':name' => $player]);

        // `room`テーブルの`participant`に参加者を追加（カンマ区切り）
        if (empty($participant)) {
            $updatedParticipants = null; // 空配列ならNULLを設定
        } else {
            $updatedParticipants = implode(",", $participant);
        }
        
        $stmt = $db->prepare("UPDATE room SET participant = :participant WHERE roomID = :room_id");
        $stmt->execute([':participant' => $updatedParticipants, ':room_id' => $room_id]);
        
        echo 
        "<script>
            alert('ルームから退出しました。ゲームID: {$room['roomID']}');
            window.location.href = 'lobby.php';
        </script>";
        
    } else {
        echo "<script>alert('退出に失敗したか、ルームが存在しません。'); window.location.href = 'game.php';</script>";
    }
} catch (PDOException $e) {
    echo "エラーが発生しました: " . $e->getMessage();
}
?>
