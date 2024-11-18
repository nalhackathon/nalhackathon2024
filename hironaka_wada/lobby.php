<?php
session_start();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>わがままカードオンライン</title>
</head>
<body>
    <h1>わがままカードオンライン</h1>

    <button onclick="location.href='index.php'" name="action">戻る</button>

    <form method="post" action="set_username.php">
        <input type="text" name="username" placeholder="ユーザー名を入力" required>
        <button type="submit" name="action" value="create_room.php">ルーム作成</button>
        <button type="submit" name="action" value="join_room.php">ルーム参加</button>
    </form>
</body>
</html>
