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

        // 定期的にサーバーにリクエストを送信してデータベースの変更をチェック
        setInterval(function() {
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'check_db_change.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.changed) {
                        console.log('update');
                        location.reload();
                    }
                }
            };
            xhr.send();
        }, 5000); // 5秒ごとにチェック
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
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        echo "データベース接続エラー: " . $e->getMessage();
        exit();
    }

    // 現在操作中のユーザー名をセッションで保持
    session_start();
    if (isset($_POST['username']) && !isset($_POST['reason'])) {
        $_SESSION['current_user'] = $_POST['username'];
        $username = $_SESSION['current_user'];

        // 既存のカードを取得
        $existingCardsSql = "SELECT card, discard FROM test";
        $existingCardsStmt = $db->query($existingCardsSql);
        $existingCards = $existingCardsStmt->fetchAll(PDO::FETCH_ASSOC);

        $usedCards = [];
        foreach ($existingCards as $cards) {
            $usedCards = array_merge($usedCards, explode(',', $cards['card']), explode(',', $cards['discard']));
        }
        $usedCards = array_map('intval', $usedCards);

        // 1~96のランダムな数字を5つ生成して重複しないようにする
        $randomNumbersArray = [];
        while (count($randomNumbersArray) < 5) {
            $randomNumber = rand(1, 96);
            if (!in_array($randomNumber, $usedCards) && !in_array($randomNumber, $randomNumbersArray)) {
                $randomNumbersArray[] = $randomNumber;
            }
        }
        $randomNumbers = implode(',', $randomNumbersArray);

        // ユーザー名をDBに挿入
        $insertSql = "INSERT INTO test (username, card, `No.`, active) VALUES (:username, :card, :no, :active)";
        $insertStmt = $db->prepare($insertSql);

        // 現在の最大No.を取得して次のNo.を決定
        $maxNoSql = "SELECT MAX(`No.`) AS max_no FROM test";
        $maxNoStmt = $db->query($maxNoSql);
        $maxNoResult = $maxNoStmt->fetch(PDO::FETCH_ASSOC);
        $nextNo = $maxNoResult['max_no'] + 1;

        try {
            $insertStmt->execute([':username' => $username, ':card' => $randomNumbers, ':no' => $nextNo, ':active' => 'false']);
            echo "新しいユーザーが作成され、カード情報が保存されました。<br>";

            // No.が1のプレイヤーのactiveをtrueにする
            if ($nextNo == 1) {
                $updateActiveSql = "UPDATE test SET active = 'true' WHERE `No.` = 1";
                $db->exec($updateActiveSql);
            }

            // フォームの再送信を防ぐためにリダイレクト
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
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

            // 既存のカードを取得
            $existingCardsSql = "SELECT card, discard FROM test";
            $existingCardsStmt = $db->query($existingCardsSql);
            $existingCards = $existingCardsStmt->fetchAll(PDO::FETCH_ASSOC);

            $usedCards = [];
            foreach ($existingCards as $cards) {
                $usedCards = array_merge($usedCards, explode(',', $cards['card']), explode(',', $cards['discard']));
            }
            $usedCards = array_map('intval', $usedCards);

            // 重複しないランダムな新しいカードを生成
            do {
                $newCard = rand(1, 96);
            } while (in_array($newCard, $cardArray) || in_array($newCard, $usedCards));

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

                // 次のプレイヤーに順番を移す
                $currentNoSql = "SELECT `No.` FROM test WHERE username = :username";
                $currentNoStmt = $db->prepare($currentNoSql);
                $currentNoStmt->execute([':username' => $username]);
                $currentNo = $currentNoStmt->fetchColumn();

                $nextNo = $currentNo + 1;

                // 現在のプレイヤーのactiveをfalseにする
                $updateActiveSql = "UPDATE test SET active = 'false' WHERE `No.` = :currentNo";
                $updateActiveStmt = $db->prepare($updateActiveSql);
                $updateActiveStmt->execute([':currentNo' => $currentNo]);

                // 次のプレイヤーのactiveをtrueにする
                $updateNextActiveSql = "UPDATE test SET active = 'true' WHERE `No.` = :nextNo";
                $updateNextActiveStmt = $db->prepare($updateNextActiveSql);
                $updateNextActiveStmt->execute([':nextNo' => $nextNo]);

                // すべてのプレイヤーのactiveがfalseの場合、1番のプレイヤーのactiveをtrueにする
                $checkAllInactiveSql = "SELECT COUNT(*) FROM test WHERE active = 'true'";
                $checkAllInactiveStmt = $db->query($checkAllInactiveSql);
                $activeCount = $checkAllInactiveStmt->fetchColumn();

                if ($activeCount == 0) {
                    $resetActiveSql = "UPDATE test SET active = 'true' WHERE `No.` = 1";
                    $db->exec($resetActiveSql);
                }

                // フォームの再送信を防ぐためにリダイレクト
                header("Location: " . $_SERVER['PHP_SELF']);
                exit();
            } catch (PDOException $e) {
                echo "エラー: " . $e->getMessage();
            }
        } else {
            echo "5枚以上のカードやメモを追加することはできません。<br>";
        }
    }

    // 現在の username と card を表示
    $displaySql = "SELECT `No.`, username, card, discard, reason, reason2, active FROM test";
    $displayStmt = $db->query($displaySql);
    $results = $displayStmt->fetchAll();
    ?>

    <h3>現在のユーザーとカード情報</h3>
    <div id="container">
        <!-- 左側：カードテーブル -->
        <table border="1" id="card-table">
            <tr>
                <th>番号</th>
                <th>ユーザー名</th>
                <th>カード</th>
                <th>捨てたカード</th>
                <th>メモ</th>
                <th>理由2</th> <!-- 追加 -->
            </tr>
            <?php foreach ($results as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['No.']) ?></td>
                    <td>
                        <?= htmlspecialchars($row['username']) ?>
                        <?php if ($row['username'] === $username && count(explode(',', $row['discard'])) >= 5): ?>
                            <button onclick="openNewWindow()">提出</button>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($row['username'] === $username): ?>
                            <?php if ($row['active'] === "true") : ?>
                                <?php foreach (explode(',', $row['card']) as $card): ?>
                                    <button onclick="displaySelectedCard('<?= htmlspecialchars($card) ?>')"><?= htmlspecialchars($card) ?></button>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php foreach (explode(',', $row['card']) as $card): ?>
                                    <button><?= htmlspecialchars($card) ?></button>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- 他のユーザーのカードは見えないようにする -->
                            <?php foreach (explode(',', $row['card']) as $card): ?>
                                <span>***</span>
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
