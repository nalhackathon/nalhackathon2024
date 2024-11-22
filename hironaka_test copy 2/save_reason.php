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

// POSTデータが存在する場合
if (isset($_POST['reason2']) && isset($_POST['cardNumber'])) {
    $reason2 = $_POST['cardNumber'] . "-" . $_POST['reason2'];

    // reason2 をデータベースに格納
    $sql = "UPDATE test SET reason2 = :reason2 WHERE username = :username";
    $stmt = $db->prepare($sql);

    // 必要に応じて適切なユーザー名を設定してください
    $username = 'YOUR_USERNAME'; // 必要に応じて変更してください
    $stmt->bindParam(':reason2', $reason2);
    $stmt->bindParam(':username', $username);

    if ($stmt->execute()) {
        echo "データが保存されました。";
    } else {
        echo "保存に失敗しました。";
    }
}
?>
