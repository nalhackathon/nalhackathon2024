<?php
session_start();

// ユーザー名をセッションに保存
if (isset($_POST['username']) && !empty($_POST['username'])) {
    $_SESSION['username'] = $_POST['username'];

    // リダイレクト先のページ（create_room.php または join_room.php）
    $action = $_POST['action'];
    header("Location: $action");
    exit;
} else {
    // ユーザー名が空の場合はエラーを表示
    echo "<script>alert('ユーザー名を入力してください。'); window.location.href = 'index.php';</script>";
    exit;
}
?>
