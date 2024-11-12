<?php
session_start();
header('Content-Type: application/json');

$host = '127.0.0.1';
$dbname = 'hackathon';
$username = 'naladmin';
$password = 'naladmin';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
} catch (PDOException $e) {
    echo json_encode(["error" => "データベース接続エラー: " . $e->getMessage()]);
    exit;
}

// ゲームを管理する変数
$action = $_GET['action'] ?? '';

// 山札の初期化
function initializeDeck() {
    $deck = range(1, 96);
    shuffle($deck);
    return $deck;
}

// ゲーム開始
if ($action === 'start') {
    $_SESSION['deck'] = initializeDeck();
    $_SESSION['hand'] = array_splice($_SESSION['deck'], 0, 5);
    $_SESSION['turn'] = 1;
    echo json_encode(["message" => "ゲームを開始しました", "hand" => $_SESSION['hand'], "turn" => $_SESSION['turn']]);
}

// カードを1枚引く
elseif ($action === 'draw') {
    if (isset($_SESSION['deck']) && !empty($_SESSION['deck'])) {
        $card = array_shift($_SESSION['deck']);
        $_SESSION['hand'][] = $card;
        echo json_encode(["card" => $card, "hand" => $_SESSION['hand']]);
    } else {
        echo json_encode(["error" => "山札がありません"]);
    }
}

// カードを捨てる
elseif ($action === 'discard') {
    $cardIndex = $_GET['index'] ?? -1;
    if ($cardIndex >= 0 && $cardIndex < count($_SESSION['hand'])) {
        $discarded = array_splice($_SESSION['hand'], $cardIndex, 1);
        $_SESSION['turn'] += 1;
        $endGame = $_SESSION['turn'] > 5;
        echo json_encode([
            "discarded" => $discarded,
            "hand" => $_SESSION['hand'],
            "turn" => $_SESSION['turn'],
            "endGame" => $endGame
        ]);
    } else {
        echo json_encode(["error" => "無効なカードインデックス"]);
    }
}

// ゲーム終了の確認
elseif ($action === 'end') {
    echo json_encode(["hand" => $_SESSION['hand'], "message" => "ゲーム終了", "turns" => $_SESSION['turn']]);
}

?>
