<?php
try {
    $db = new PDO('mysql:dbname=hironaka;host=127.0.0.1;charset=utf8mb4', 'naladmin', 'naladmin');
} catch (PDOException $e) {
    echo "データベース接続エラー :" . $e->getMessage();
}

// ボタンが押された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (preg_match('/^afkbtn(\d+)$/', $key, $matches)) {
            $seat_num = $matches[1];
            $sql = "UPDATE seat SET sm_state = '離席中' WHERE sm_num = :seat_num";
            $stmt = $db->prepare($sql);
            $stmt->execute([':seat_num' => $seat_num]);
        } elseif (preg_match('/^afkbtn(\d+)_r$/', $key, $matches)) {
            $seat_num = $matches[1];
            $sql = "UPDATE seat SET sm_state = '利用中' WHERE sm_num = :seat_num";
            $stmt = $db->prepare($sql);
            $stmt->execute([':seat_num' => $seat_num]);
        }
    }
}

// 座席情報を取得
$sql = 'SELECT * FROM seat';
$result = $db->query($sql);
$seats = [];
while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $seats[] = [
        'num' => $row['sm_num'],
        'state' => $row['sm_state'],
        'name' => $row['sm_name'],
        'code' => $row['sm_code']
    ];
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="homeStyle.css">
    <title>ホーム画面</title>
</head>

<body>
    <div class="header">
        <img src="NALlogo.png" alt="Logo" class="logo">
        <div class="date-time" id="date-time"></div>
        <div class="room-name">会議室</div>
        <div class="seat-info">
            <?php
            $total_seats = count($seats);
            $occupied_seats = 0;

            foreach ($seats as $seat) {
                if ($seat['state'] == '利用中' || $seat['state'] == '離席中' || $seat['state'] == '予約中') {
                    $occupied_seats++;
                }
            }

            $empty_seats = $total_seats - $occupied_seats;
            echo "座席数: {$total_seats}、空席数: {$empty_seats}";
            ?>
        </div>
    </div>
    <div class="container">
        <?php foreach ($seats as $seat): ?>
            <?php
            $class = '';
            switch ($seat['state']) {
                case '利用中':
                    $class = 'occupied';
                    break;
                case '離席中':
                    $class = 'afk';
                    break;
                case '空席':
                    $class = 'empty';
                    break;
                case '予約中':
                    $class = 'reserved';
                    break;
                default:
                    $class = strtolower($seat['state']);
                    break;
            }
            ?>
            <div class="square <?php echo $class; ?>">
                <div class="status-text"><?php echo $seat['state']; ?></div>
                <?php if ($seat['state'] == '利用中'): ?>
                    <div class="button-container">
                        <form method="post">
                            <button class="btn afk" type="submit" name="afkbtn<?php echo $seat['num']; ?>"><i
                                    class="fas fa-sign-out-alt"></i> 離席する</button>
                        </form>
                    </div>
                <?php elseif ($seat['state'] == '離席中'): ?>
                    <div class="button-container">
                        <form method="post">
                            <button class="btn afk-release" type="submit" name="afkbtn<?php echo $seat['num']; ?>_r"><i
                                    class="fas fa-sign-in-alt"></i> 離席解除</button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="footer-buttons">
        <form method="post">
            <button class="footer-btn" type="submit" name="reserve">
                <i class="fas fa-calendar-check"></i> 予約する</button>
            <button class="footer-btn cancel" type="submit" name="cancel">
                <i class="fas fa-times-circle"></i>利用中止</button>
        </form>
    </div>

    <?php
    // 予約するボタンが押された場合の処理
    if (isset($_POST['reserve'])) {
        // ここでは画面遷移のみを示します
        header("Location: test2.php"); // 遷移先のURLを指定します
        exit();
    }

    // 利用中止するボタンが押された場合の処理
    if (isset($_POST['cancel'])) {
        // ここでは画面遷移のみを示します
        header("Location: test2.php"); // 遷移先のURLを指定します
        exit();
    }
    ?>
    <script>
        function updateDateTime() {
            const now = new Date();
            const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit' };
            document.getElementById('date-time').textContent = now.toLocaleString('ja-JP', options);
        }
        updateDateTime();
        setInterval(updateDateTime, 60000);
    </script>
</body>

</html>