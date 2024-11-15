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

// POSTで送信されたデータの処理
if (isset($_POST['cardNumber']) && isset($_POST['memo'])) {
    $cardNumber = $_POST['cardNumber'];
    $memo = $_POST['memo'];

    // カード番号に関連付けてreason2を更新
    $sql = "SELECT reason2 FROM test WHERE FIND_IN_SET(:cardNumber, card) LIMIT 1";
    $stmt = $db->prepare($sql);
    $stmt->execute([':cardNumber' => $cardNumber]);
    $result = $stmt->fetch();

    $existingReason2 = $result['reason2'] ?? '';
    $newReason2 = trim($existingReason2 . "; " . $cardNumber . "-" . $memo);

    // reason2を更新
    $updateSql = "UPDATE test SET reason2 = :reason2 WHERE FIND_IN_SET(:cardNumber, card)";
    $updateStmt = $db->prepare($updateSql);
    $updateStmt->execute([
        ':reason2' => $newReason2,
        ':cardNumber' => $cardNumber
    ]);

    // 保存後、new_window.phpにリダイレクト
    header("Location: new_window.php");
    exit();
}
?>
