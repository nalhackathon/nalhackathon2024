<?php
require_once 'db.php';

session_start();
$username = $_SESSION['username'] ?? '';

// エラーチェック
if (empty($username)) {
    echo "<script>alert('ユーザー名が設定されていません。タイトル画面に戻ってください。'); window.location.href = 'index.php';</script>";
    exit;
}

// ランダムなゲームIDの生成（例としてランダムなIDを使用）
$game_id = rand(1000, 9999);

try {
    // `games`テーブルに新しいゲームを追加
    $stmt = $db->prepare("INSERT INTO games (game_id, status, current_turn, max_turns) VALUES (:game_id, 'waiting', 0, 5)");
    $stmt->execute([':game_id' => $game_id]);

    // `room`テーブルに新しいルームを作成し、作成者情報を保存
    $stmt = $db->prepare("INSERT INTO room2 (roomID, creater, participant, state) VALUES (:room_id, :creater, :creater, :state)");
    $stmt->execute([':room_id' => $game_id, ':creater' => $username, ':state' => "wait"]);

    // `players`テーブルに作成者を追加
    $stmt = $db->prepare("INSERT INTO players (game_id, name) VALUES (:game_id, :name)");
    $stmt->execute([':game_id' => $game_id, ':name' => $username]);

    // ゲームID、プレイヤーIDをセッションに保存
    $_SESSION['game_id'] = $game_id;
    $_SESSION['player_id'] = $username;

    echo "<script>
        alert('ルームが作成されました。ゲームID: $game_id');
        window.location.href = 'game.php';
    </script>";
} catch (PDOException $e) {
    echo "エラーが発生しました: " . $e->getMessage();
}
?>
