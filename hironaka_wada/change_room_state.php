<?php
require_once 'db.php';

session_start();
$room_id = $_SESSION['game_id'] ?? '';

// エラーチェック
if (empty($username)) {
    echo "<script>alert('ユーザー名が設定されていません。タイトル画面に戻ってください。'); window.location.href = 'index.php';</script>";
    exit;
}
if (empty($room_id)) {
    echo "<script>alert('ユーザー名が設定されていません。タイトル画面に戻ってください。'); window.location.href = 'index.php';</script>";
    exit;
}

// POSTリクエストからルームstateを取得
$player = $_POST['player_id'];

try {
    // `room`テーブルで指定されたルームIDの存在を確認
    $stmt = $db->prepare("SELECT roomID, participant FROM room WHERE roomID = :room_id");
    $stmt->execute([':room_id' => $room_id]);
    $room = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($room) {

        // ゲームID、プレイヤーIDをセッションに保存
        $_SESSION['game_id'] = $room['roomID'];
        $_SESSION['player_id'] = $player;

        echo "<script>
            alert('ゲームを開始します。プレイヤーID: {$player}');
            window.location.href = 'submit_name.php';
        </script>";
    } else {
        echo "<script>alert('状態変更に失敗しました。'); window.location.href = 'game.php';</script>";
    }
} catch (PDOException $e) {
    echo "エラーが発生しました: " . $e->getMessage();
}

