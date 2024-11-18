<?php
session_start();
require_once 'db.php';

// ゲームIDとプレイヤーIDの取得
$game_id = $_SESSION['game_id'] ?? null;
$player_id = $_SESSION['player_id'] ?? null;

if (!$game_id || !$player_id) {
    echo "ルームまたはプレイヤーが存在していません。";
    exit;
}

// ルーム作成者（ホスト）の取得
$stmt = $db->prepare("SELECT creater FROM room2 WHERE roomID = :game_id");
$stmt->execute([':game_id' => $game_id]);
$room = $stmt->fetch(PDO::FETCH_ASSOC);
$host = $stmt->fetch(PDO::FETCH_ASSOC)['creater'] ?? null;

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
        <button type="submit" value="<?php echo $player_id; ?>" name="game_id">ゲーム開始</button>
    </form>

    <a href="join_room.php">ルーム一覧に戻る</a>
    </body>

    <script>
        const gameId = <?php echo json_encode($game_id); ?>;

        // プレイヤーリストを取得して表示する関数
        function fetchPlayers() {
            fetch('game.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'fetch_players=true'
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const playerList = document.getElementById('player-list');
                    playerList.innerHTML = '';  // リストをクリア
                    data.players.forEach(player => {
                        const listItem = document.createElement('li');
                        listItem.textContent = player.name;
                        playerList.appendChild(listItem);
                    });
                } else {
                    console.error("Error fetching players:", data.message);
                }
            })
            .catch(error => console.error('Fetch error:', error));
        }

        

        // 5秒ごとにプレイヤーリストを更新
        setInterval(fetchPlayers, 5000);
        fetchPlayers(); // 初回のリスト表示

        
    </script>
</body>
</html>
