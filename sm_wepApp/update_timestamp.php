<?php
try {
    $db = new PDO('mysql:dbname=hironaka;host=127.0.0.1;charset=utf8mb4', 'naladmin', 'naladmin');
} catch (PDOException $e) {
    echo "データベース接続エラー :" . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update']) && $_POST['update'] === 'true') {
    $stmt = $db->prepare('UPDATE seat SET updated_at = NOW()');
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    echo 'invalid_request';
}
?>
