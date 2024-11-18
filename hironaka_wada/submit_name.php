<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>カードゲーム</title>
    <style>
        #container {
            display: flex;
            justify-content: space-between;
        }
        #card-table {
            width: 60%;
        }
        #selected-card {
            width: 35%;
            border: 1px solid #000;
            padding: 10px;
            height: auto;
        }
        #reason-input {
            margin-top: 10px;
            width: 100%;
        }
    </style>
    <script>
        function displaySelectedCard(cardNumber) {
            document.getElementById("selected-card-display").innerText = "選択したカード: " + cardNumber;
            document.getElementById("selected-card-number").value = cardNumber;
            document.getElementById("reason-input").style.display = "block";
            document.getElementById("save-reason-button").style.display = "block";
        }

        function openNewWindow() {
            window.open('new_window.php', '_blank', 'width=600,height=400');
        }
    </script>
</head>
<body>
    <h3>名前を入力してください</h3>
    <!-- 名前入力フォーム -->
    <form method="POST" action="">
        <label for="username">名前:</label>
        <input type="text" id="username" name="username" required>
        <button type="submit">送信</button>
    </form>

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

    // 現在操作中のユーザー名をセッションで保持
    session_start();
    if (isset($_SESSION['player_id']) && !isset($_POST['reason'])) {
        $_SESSION['current_user'] = $_SESSION['player_id'];
        $username = $_SESSION['current_user'];
        
        // 1~96のランダムな数字を5つ生成して文字列に変換
        $randomNumbersArray = array_map(function() { return rand(1, 96); }, range(1, 5));
        $randomNumbers = implode(',', $randomNumbersArray);

        // ユーザー名をDBに挿入
        $insertSql = "INSERT INTO test (username, card) VALUES (:username, :card)";
        $insertStmt = $db->prepare($insertSql);

        try {
            $insertStmt->execute([':username' => $username, ':card' => $randomNumbers]);
            echo "新しいユーザーが作成され、カード情報が保存されました。<br>";
        } catch (PDOException $e) {
            echo "エラー: " . $e->getMessage();
        }
    } else {
        $username = $_SESSION['current_user'] ?? null;
    }

    // メモの内容と選択したカードをDBに格納
    if (isset($_POST['reason']) && isset($_POST['selected_card']) && $username) {
        $reason = $_POST['selected_card'] . "-" . $_POST['reason'];
        $selectedCard = $_POST['selected_card'];

        // 現在の discard, reason, card を取得
        $getInfoSql = "SELECT discard, reason, card FROM test WHERE username = :username";
        $getInfoStmt = $db->prepare($getInfoSql);
        $getInfoStmt->execute([':username' => $username]);
        $currentInfo = $getInfoStmt->fetch();

        // discardとreasonを配列に変換
        $discardArray = $currentInfo['discard'] ? explode(',', $currentInfo['discard']) : [];
        $reasonArray = $currentInfo['reason'] ? explode(',', $currentInfo['reason']) : [];
        $cardArray = $currentInfo['card'] ? explode(',', $currentInfo['card']) : [];

        // discardとreasonが5つ未満の場合のみ追加
        if (count($discardArray) < 5 && count($reasonArray) < 5) {
            $discardArray[] = $selectedCard;
            $reasonArray[] = $reason;

            // 現在のカードから捨てたカードを削除
            $cardArray = array_diff($cardArray, [$selectedCard]);

            // 重複しないランダムな新しいカードを生成
            do {
                $newCard = rand(1, 96);
            } while (in_array($newCard, $cardArray));

            $cardArray[] = $newCard;

            $newDiscard = implode(',', $discardArray);
            $newReason = implode(',', $reasonArray);
            $newCardList = implode(',', $cardArray);

            // 更新クエリ
            $updateSql = "UPDATE test SET discard = :discard, reason = :reason, card = :card WHERE username = :username";
            $updateStmt = $db->prepare($updateSql);

            try {
                $updateStmt->execute([':discard' => $newDiscard, ':reason' => $newReason, ':card' => $newCardList, ':username' => $username]);
                echo "メモが保存され、カードが捨てられ、新しいカードが補充されました。<br>";
            } catch (PDOException $e) {
                echo "エラー: " . $e->getMessage();
            }
        } else {
            echo "5枚以上のカードやメモを追加することはできません。<br>";
        }
    }

    // 現在の username と card を表示
    $displaySql = "SELECT username, card, discard, reason FROM test";
    $displayStmt = $db->query($displaySql);
    $results = $displayStmt->fetchAll();
    ?>

    <h3>現在のユーザーとカード情報</h3>
    <div id="container">
        <!-- 左側：カードテーブル -->
        <table border="1" id="card-table">
            <thead>
                <tr>
                    <th>ユーザー名</th>
                    <th>カード</th>
                    <th>捨てたカード</th>
                    <th>メモ</th>
                </tr>
            </thead>
            <tbody>
                <!-- 自動更新により内容が上書きされる -->
            </tbody>
        </table>

        <!-- 右側：選択したカードの表示エリア -->
        <div id="selected-card">
            <p id="selected-card-display">ここに選択したカードが表示されます。</p>
            <!-- メモ入力用フォーム -->
            <form method="POST" action="">
                <input type="hidden" name="username" value="<?= htmlspecialchars($username) ?>">
                <input type="hidden" id="selected-card-number" name="selected_card">
                <input type="text" id="reason-input" name="reason" placeholder="カードについてのメモを入力" style="display: none;">
                <button type="submit" id="save-reason-button" style="display: none;">メモを保存</button>
            </form>
        </div>

        <!-- 提出ボタンを表示 -->
        <?php if (isset($row) && $row['username'] === $username && count(explode(',', $row['discard'])) >= 5): ?>
            <button id="submit-button" onclick="openNewWindow()">提出</button>
        <?php endif; ?>
    </div>

    <script>
        function updateTable() {
            fetch('fetch_data.php')
                .then(response => response.json())
                .then(data => {
                    const tableBody = document.querySelector("#card-table tbody");
                    tableBody.innerHTML = ""; // テーブルの内容をクリア

                    data.forEach(row => {
                        const tr = document.createElement("tr");

                        // ユーザー名列
                        const usernameTd = document.createElement("td");
                        usernameTd.textContent = row.username;
                        tr.appendChild(usernameTd);

                        // カード列
                        const cardTd = document.createElement("td");
                        const cards = row.card.split(',');
                        cards.forEach(card => {
                            if (row.username === "<?= htmlspecialchars($username) ?>") {
                                const button = document.createElement("button");
                                button.textContent = card;
                                button.onclick = () => displaySelectedCard(card);
                                cardTd.appendChild(button);
                            } else {
                                const span = document.createElement("span");
                                span.textContent = card;
                                cardTd.appendChild(span);
                                cardTd.appendChild(document.createTextNode(" ")); // スペースを追加
                            }
                        });
                        tr.appendChild(cardTd);

                        // 捨てたカード列
                        const discardTd = document.createElement("td");
                        discardTd.textContent = row.discard || "";
                        tr.appendChild(discardTd);

                        // メモ列
                        const reasonTd = document.createElement("td");
                        if (row.reason) {
                            reasonTd.innerHTML = row.reason.split(',').map(reason => `<div>${reason}</div>`).join('');
                        }
                        tr.appendChild(reasonTd);

                        tableBody.appendChild(tr);
                    });
                })
                .catch(error => console.error('テーブル更新エラー:', error));
        }

        // 5秒ごとにテーブルを更新
        setInterval(updateTable, 5000);

        // ページ読み込み時に1度更新
        updateTable();
    </script>

</body>
</html>
