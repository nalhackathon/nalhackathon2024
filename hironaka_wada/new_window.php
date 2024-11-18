<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>カード情報</title>
    <style>
        #container {
            display: flex;
            justify-content: space-between;
        }
        #card-display {
            width: 35%;
            border: 1px solid #000;
            padding: 10px;
            height: auto;
        }
    </style>
    <script>
        function displayCard(cardNumber) {
            const cardDisplay = document.getElementById("card-display");
            cardDisplay.innerHTML = `
                <p>選択したカード番号: ${cardNumber}</p>
                <form method="POST" action="save_reason2.php">
                    <input type="hidden" name="cardNumber" value="${cardNumber}">
                    <input type="text" name="memo" placeholder="メモを入力" required>
                    <button type="submit">送信</button>
                </form>
            `;
        }
    </script>
</head>
<body>
    <h3>ユーザー名とカード情報</h3>

    <div id="container">
        <!-- 左側：ユーザーとカード情報 -->
        <div id="card-table">
            <?php
            // データベース接続情報
            $host = '127.0.0.1';
            $dbname = 'hackathon';
            $username = 'naladmin';
            $password = 'naladmin';

            try {
                $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            } catch (PDOException $e) {
                echo "データベース接続エラー: " . $e->getMessage();
                exit();
            }

            // データベースから username, card, reason2 を取得して表示
            $sql = "SELECT username, card, reason2 FROM test";
            $stmt = $db->query($sql);
            $results = $stmt->fetchAll();

            if ($results) {
                echo "<table border='1'>";
                echo "<tr><th>ユーザー名</th><th>カード</th><th>理由2 (reason2)</th></tr>";
                foreach ($results as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                    echo "<td>";
                    
                    // カードをカンマで分割してボタンとして表示
                    $cards = explode(',', $row['card']);
                    foreach ($cards as $card) {
                        echo "<button onclick='displayCard(\"" . htmlspecialchars($card) . "\")'>" . htmlspecialchars($card) . "</button> ";
                    }
                    echo "</td>";
                    echo "<td>" . htmlspecialchars($row['reason2']) . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "<p>データがありません。</p>";
            }
            ?>
        </div>

        <!-- 右側：選択したカードの表示エリア -->
        <div id="card-display">
            <h3>選択したカード</h3>
        </div>
    </div>
</body>
</html>
