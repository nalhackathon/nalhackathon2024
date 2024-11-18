<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ルームに参加</title>
</head>
<body>
    <h1>ルームに参加</h1>
    <form action="join_room_process.php" method="post">
        <label for="room_id">ルームIDを入力:</label>
        <input type="text" name="room_id" required>
        <button type="submit">参加</button>
    </form>
    <button onclick="location.href='lobby.php'" name="action">戻る</button>
</body>
</html>
