<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>カードゲーム</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        h3 {
            text-align: center;
            margin-top: 20px;
        }
        form {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        form input, form button {
            margin: 0 5px;
            padding: 10px;
            font-size: 16px;
        }
        #container {
            display: flex;
            justify-content: space-around;
            padding: 20px;
        }
        #card-table {
            width: 60%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #card-table th, #card-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        #selected-card {
            width: 35%;
            border: 1px solid #ddd;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        #reason-input {
            margin-top: 10px;
            width: 100%;
            padding: 10px;
            font-size: 16px;
        }
        #save-reason-button {
            margin-top: 10px;
            padding: 10px;
            font-size: 16px;
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
        <input type="text" id="username" name="username" placeholder="名前" required>
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
    if (isset($_POST['username']) && !isset($_POST['reason'])) {
        $_SESSION['current_user'] = $_POST['username'];
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
    $displaySql = "SELECT username, card, discard, reason, reason2 FROM test";
    $displayStmt = $db->query($displaySql);
    $results = $displayStmt->fetchAll();
    ?>

    <h3>現在のユーザーとカード情報</h3>
    <div id="container">
        <!-- 左側：カードテーブル -->
        <table id="card-table">
            <tr>
                <th>ユーザー名</th>
                <th>カード</th>
                <th>捨てたカード</th>
                <th>メモ</th>
                <th>理由2</th> <!-- 追加 -->
            </tr>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($row['username']) ?>
                        <?php if ($row['username'] === $username && count(explode(',', $row['discard'])) >= 5): ?>
                            <button onclick="openNewWindow()">提出</button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['username'] === $username): ?>
                            <?php foreach (explode(',', $row['card']) as $card): ?>
                                <button onclick="displaySelectedCard('<?= htmlspecialchars($card) ?>')"><?= htmlspecialchars($card) ?></button>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <?php foreach (explode(',', $row['card']) as $card): ?>
                                <span><?= htmlspecialchars($card) ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['discard']) ?></td>
                    <td><?= nl2br(htmlspecialchars(implode("\n", explode(',', $row['reason'])))) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['reason2'])) ?></td> <!-- 追加 -->
                </tr>
            <?php endforeach; ?>
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
    </div>
</body>
</html>
