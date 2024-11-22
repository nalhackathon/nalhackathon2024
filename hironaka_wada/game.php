<?php
session_start();
require_once 'db.php';

// ゲームIDとプレイヤーIDの取得
$game_id = $_SESSION['game_id'] ?? null;
$player_id = $_SESSION['player_id'] ?? null;

// ゲームIDとプレイヤーIDが存在するかの確認
if (!$game_id || !$player_id) {
    "<script>
        alert('ルームまたはプレイヤーが設定されていません。;
        window.location.href = 'lobby.php';
    </script>";
}

// ルーム作成者（ホスト）の取得
$stmt = $db->prepare("SELECT creater FROM room WHERE roomID = :game_id");
$stmt->execute([':game_id' => $game_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
$host = $room['creater'] ?? null;

// クライアントからのfetchリクエストかどうかを判別
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_players'])) {
    try {
        // `players`テーブルから現在のゲームの参加者リストを取得
        $stmt = $db->prepare("SELECT name FROM players WHERE game_id = :game_id");
        $stmt->execute([':game_id' => $game_id]);
        $players = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 各プレイヤー名にホスト情報を追加
        foreach ($players as &$player) {
            if ($player['name'] === $host) {
                $player['name'] .= " (ホスト)";
            }
        }
        echo json_encode(['status' => 'success', 'players' => $players]);


    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}

// 参加者状態をチェックするAPI
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_room'])) {
    try {
        $stmt = $db->prepare("SELECT participant FROM room WHERE roomID = :game_id");
        $stmt->execute([':game_id' => $game_id]);
        $room = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($room['participant'] === null) {
            echo json_encode(['status' => 'empty']);
        } else {
            echo json_encode(['status' => 'active']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>わがままカードオンライン - ターン制カードゲーム</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; }
        #action-buttons { display: none; margin-top: 20px; }
        button { padding: 10px 20px; font-size: 16px; }
    </style>
</head>
<body>
    <h1>わがままカードオンライン</h1>
    <h2>ゲーム参加者</h2>
    
    <ul id="player-list">
        <!-- 参加者リストがここに表示されます -->
    </ul>

    <!-- ゲーム開始ボタン -->
    <form action="change_room_state.php" method="post">
        <button type="submit" value="<?php echo $player_id; ?>" name="player_id">ゲーム開始</button>
    </form>

    <!-- ルーム削除ボタン(ホストのみ) -->
    <?php if($player_id === $host): ?>
        <form action="delete_room.php" method="post">
            <button type="submit" value="<?php echo $player_id; ?>" name="player_id">ルーム削除</button>
        </form>
    <?php endif; ?>

    <!-- 退出ボタン(ホスト以外) -->
    <?php if($player_id != $host): ?>
        <form action="quit_room.php" method="post">
            <button type="submit" value="<?php echo $player_id; ?>" name="player_id">退出する</button>
        </form>
    <?php endif; ?>

</body>

<script>
    function fetchPlayers() {
        const gameId = <?php echo json_encode($game_id); ?>;
        fetch('game.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'fetch_players=true'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const playerList = document.getElementById('player-list');
                if (playerList) {
                    playerList.innerHTML = '';  // Clear the list
                    data.players.forEach(player => {
                        const listItem = document.createElement('li');
                        listItem.textContent = player.name;
                        playerList.appendChild(listItem);
                    });
                }
            } else {
                const errorMsg = document.getElementById('error-message');
                if (errorMsg) {
                    errorMsg.textContent = "エラー: プレイヤーリストの取得に失敗しました。";
                }
            }
        })
        .catch(error => {
            const errorMsg = document.getElementById('error-message');
            if (errorMsg) {
                errorMsg.textContent = "通信エラーが発生しました。";
            }
            console.error('Fetch error:', error);
        });
    }

    function checkRoom() {
        fetch('game.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'check_room=true'
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'empty') {
                alert('参加者がいません。ロビーに戻ります。');
                window.location.href = 'lobby.php';
            }
        })
        .catch(error => {
            console.error('チェックエラー:', error);
        });
    }

    // ページがアクティブな場合のみリストを更新
    let isActive = true;
    window.addEventListener('focus', () => isActive = true);
    window.addEventListener('blur', () => isActive = false);

    function fetchPlayersPeriodically() {
        if (isActive) {
            fetchPlayers();
            checkRoom(); // Include room check
        }
        setTimeout(fetchPlayersPeriodically, 5000); // Continue the loop
    }

    fetchPlayersPeriodically(); // Start periodic updates
</script>

</body>
</html>
