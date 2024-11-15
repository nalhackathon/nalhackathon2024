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

// カードをランダムに1~96から生成する関数
function generateRandomCard() {
    return rand(1, 96);
}

// ユーザー名を取得（POST または GET から）
$username = $_POST['username'] ?? $_GET['username'] ?? null;

if ($username) {
    // 1~96のランダムな数字を5つ生成して文字列に変換
    $randomNumbersArray = array_map(function() { return generateRandomCard(); }, range(1, 5));
    $randomNumbers = implode(',', $randomNumbersArray);

    // ユーザーがすでに存在するかを確認
    $checkSql = "SELECT * FROM test WHERE username = :username";
    $checkStmt = $db->prepare($checkSql);
    $checkStmt->execute([':username' => $username]);
    $user = $checkStmt->fetch();

    if ($user) {
        // 既存のユーザーがいる場合、cardフィールドを更新
        $updateSql = "UPDATE test SET card = :card WHERE username = :username";
        $updateStmt = $db->prepare($updateSql);
        $updateStmt->execute([':card' => $randomNumbers, ':username' => $username]);
        echo "既存のユーザーのカード情報が更新されました。<br>";
    } else {
        // 新規ユーザーの場合、usernameとcardを挿入
        $insertSql = "INSERT INTO test (username, card) VALUES (:username, :card)";
        $insertStmt = $db->prepare($insertSql);
        $insertStmt->execute([':username' => $username, ':card' => $randomNumbers]);
        echo "新しいユーザーが作成され、カード情報が保存されました。<br>";
    }
}

// 現在の username と card を表示
$displaySql = "SELECT username, card, discard FROM test";
$displayStmt = $db->query($displaySql);
$results = $displayStmt->fetchAll();

echo "<h3>現在のユーザーとカード情報</h3>";
echo "<table border='1'>";
echo "<tr><th>ユーザー名</th><th>カード</th><th>捨てたカード</th></tr>";
foreach ($results as $row) {
    echo "<tr><td>{$row['username']}</td><td>";

    // カードをボタンとして表示
    $cards = explode(',', $row['card']);
    foreach ($cards as $card) {
        echo "<form method='POST' action='discard_card.php' style='display:inline;'>";
        echo "<input type='hidden' name='username' value='{$row['username']}'>";
        echo "<input type='hidden' name='discard_card' value='{$card}'>";
        echo "<button type='submit'>{$card}</button>";
        echo "</form> ";
    }

    echo "</td><td>{$row['discard']}</td></tr>";
}
echo "</table>";
?>
