<?php
try {
    $db = new PDO('mysql:dbname=hironaka;host=127.0.0.1;charset=utf8mb4', 'naladmin', 'naladmin');
} catch (PDOException $e) {
    echo "データベース接続エラー :" . $e->getMessage();
    exit();
}

session_start();

if (!isset($_SESSION['last_update'])) {
    $_SESSION['last_update'] = null;
}

$sql = 'SELECT MAX(updated_at) as last_update FROM seat';
$result = $db->query($sql);
if ($result === false) {
    echo "SQLエラー: " . implode(", ", $db->errorInfo());
    exit();
}
$row = $result->fetch(PDO::FETCH_ASSOC);
$lastUpdate = $row['last_update'];

if ($_SESSION['last_update'] != $lastUpdate) {
    $_SESSION['last_update'] = $lastUpdate;
    echo 'update';
} else {
    echo 'no_update';
}
?>
