<?php
try {
    $db = new PDO('mysql:dbname=hironaka;host=127.0.0.1;charset=utf8mb4', 'naladmin', 'naladmin');
} catch (PDOException $e) {
    echo "データベース接続エラー :" . $e->getMessage();
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['seat_num']) && isset($_POST['action'])) {
        $seatNum = (int) $_POST['seat_num'];
        if ($_POST['action'] === 'reserve' && isset($_POST['name'])) {
            $name = $_POST['name'];
            $stmt = $db->prepare('UPDATE seat SET sm_state = "予約中", sm_name = :name, updated_at = NOW() WHERE sm_num = :seat_num');
            $stmt->execute(['name' => $name, 'seat_num' => $seatNum]);
            echo 'update'; // 座席の状態を変更した後に 'update' を返す
        } elseif ($_POST['action'] === 'cancel') {
            $stmt = $db->prepare('UPDATE seat SET sm_state = "空席", sm_name = NULL, sm_code = NULL, updated_at = NOW() WHERE sm_num = :seat_num');
            $stmt->execute(['seat_num' => $seatNum]);
            echo 'update'; // 座席の状態を変更した後に 'update' を返す
        } elseif ($_POST['action'] === 'update' && isset($_POST['state'])) {
            $state = $_POST['state'];
            $stmt = $db->prepare('UPDATE seat SET sm_state = :state, updated_at = NOW() WHERE sm_num = :seat_num');
            $stmt->execute(['state' => $state, 'seat_num' => $seatNum]);
            echo 'update'; // 座席の状態を変更した後に 'update' を返す
        }
        exit();
    }
}

// 座席情報を取得
$sql = 'SELECT * FROM seat';
$result = $db->query($sql);
$seats = [];
$availableSeats = 0; // 空席数カウント
$totalSeats = 0; // 総座席数カウント

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $seats[$row['sm_num']] = [
        'state' => $row['sm_state'],
        'name' => $row['sm_name']
    ];
    $totalSeats++;
    if ($row['sm_state'] === '空席') {
        $availableSeats++;
    }
}

// 座席の状態に基づくCSSクラスを返す関数
function getSeatStateClass($seat)
{
    switch ($seat['state']) {
        case '利用中':
            return 'occupied';
        case '離席中':
            return 'afk';
        case '空席':
            return 'available';
        case '予約中':
            return 'reserved';
        default:
            return 'unknown';
    }
}

// 座席の名前を返す関数
function getSeatName($seat)
{
    return $seat['name'] ? explode(" ", $seat['name'])[0] : ''; // 苗字のみ表示
}

// 現在の日付
$currentDate = date('Y-m-d');
?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>座席管理</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="teststyle.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <img src="NALlogo.png" alt="ロゴ" class="logo-img">
            </div>
            <div class="name-input-form">
                <form id="name-form">
                    <label for="lastname">苗字:</label>
                    <input type="text" id="lastname" name="lastname" maxlength="3" required>
                    <label for="firstname">名前:</label>
                    <input type="text" id="firstname" name="firstname" maxlength="3" required>
                    <button type="submit">入力</button>
                </form>
                <div id="name-display"></div>
            </div>
            <div class="seat-count">
                総座席数: <?php echo $totalSeats; ?> / 空席数: <?php echo $availableSeats; ?>
            </div>
        </div>

        <div class="carousel-container">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                    <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="NALlogo.png" alt="First slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="c2.png" alt="Second slide">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="c3.jpg" alt="Third slide">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>

        <div class="hamburger-menu">
            <button class="hamburger" id="hamburger-button">
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
                <span class="hamburger-line"></span>
            </button>
            <div class="hamburger-menu-content" id="hamburger-menu-content">
                <a href="#" id="menu1">会議室状況</a>
                <a href="#" id="menu2">グループ会社</a>
                <a href="#" id="menu3">背景変更</a>
            </div>
        </div>

        <div class="seat-layout">
            <!-- 左側の列のさらに左側に社長席を追加 -->
            <div class="column">
                <div class="row">
                    <div class="long-seat" id="seat-CEO" data-full-name="社長席">
                        社長席
                    </div>
                </div>
                <div class="row">
                    <div class="long-seat empty-space" id="seat-left-space" data-full-name=""></div>
                </div>
            </div>

            <!-- 左側の列 -->
            <div class="column">
                <?php for ($i = 17; $i >= 13; $i--): ?>
                    <div class="row">
                        <div class="seat <?php echo getSeatStateClass($seats[$i]); ?>" id="seat-<?php echo $i; ?>"
                            data-full-name="<?php echo $seats[$i]['name']; ?>">
                            <?php echo getSeatName($seats[$i]); ?>
                        </div>
                        <div class="seat <?php echo getSeatStateClass($seats[$i + 5]); ?>" id="seat-<?php echo $i + 5; ?>"
                            data-full-name="<?php echo $seats[$i + 5]['name']; ?>">
                            <?php echo getSeatName($seats[$i + 5]); ?>
                        </div>
                    </div>
                <?php endfor; ?>
                <div class="row">
                    <div class="long-seat <?php echo getSeatStateClass($seats[12]); ?>" id="seat-12"
                        data-full-name="<?php echo $seats[12]['name']; ?>">
                        <?php echo getSeatName($seats[12]); ?>
                    </div>
                </div>
            </div>

            <!-- 右側の列 -->
            <div class="column">
                <div class="row">
                    <div class="long-seat <?php echo getSeatStateClass($seats[11]); ?>" id="seat-11"
                        data-full-name="<?php echo $seats[11]['name']; ?>">
                        <?php echo getSeatName($seats[11]); ?>
                    </div>
                </div>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <div class="row">
                        <div class="seat <?php echo getSeatStateClass($seats[$i]); ?>" id="seat-<?php echo $i; ?>"
                            data-full-name="<?php echo $seats[$i]['name']; ?>">
                            <?php echo getSeatName($seats[$i]); ?>
                        </div>
                        <div class="seat <?php echo getSeatStateClass($seats[$i + 5]); ?>" id="seat-<?php echo $i + 5; ?>"
                            data-full-name="<?php echo $seats[$i + 5]['name']; ?>">
                            <?php echo getSeatName($seats[$i + 5]); ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>

            <!-- 右側のさらに右側に追加する座席 -->
            <div class="column">
                <?php for ($i = 3; $i <= 6; $i++): ?>
                    <div class="row">
                        <div class="seat" id="extra-seat-<?php echo $i * 2 - 1; ?>" data-full-name="extra"></div>
                        <div class="seat" id="extra-seat-<?php echo $i * 2; ?>" data-full-name="extra"></div>
                    </div>
                <?php endfor; ?>
                <div class="row">
                    <div class="long-seat" id="extra-long-seat" data-full-name="extra"></div>
                </div>
            </div>
        </div>
        <div id="seat-info-bubble" class="seat-info-bubble"></div>

        <div id="popup1" class="popup">
            <div class="popup-content">
                <span class="close-button" id="close-popup1">&times;</span>
                <div class="room" id="large-meeting-room">
                    <h3>大会議室</h3>
                    <p>状態: 利用可能</p>
                </div>
                <div class="room" id="small-meeting-room">
                    <h3>小会議室</h3>
                    <p>状態: 利用可能</p>
                </div>
                <div class="room" id="round-table">
                    <h3>丸テーブル</h3>
                    <p>状態: 利用可能</p>
                </div>
            </div>
        </div>

        <div id="popup2" class="popup">
            <div class="popup-content">
                <span class="close-button" id="close-popup2">&times;</span>
                <div class="image-gallery">
                <!-- -------------------------------------------------------------------------------- -->
                    <a href="https://www.qtnet.co.jp/" target="_blank"><img src="Qtnetlogo.png" alt="Group 1" class="group-image"></a>
                    <a href="https://qtmedia.co.jp/" target="_blank"><img src="Qtmedialogo.svg" alt="Group 2" class="group-image"></a>
                    <a href="https://sengokugaming.com/" target="_blank"><img src="Sengokulogo.png" alt="Group 3" class="group-image"></a>
                <!-- -------------------------------------------------------------------------------- -->
                </div>
            </div>
        </div>

        <div id="popup3" class="popup">
            <div class="popup-content">
                <span class="close-button" id="close-popup3">&times;</span>
                <!-- ------------------------------------------------------------------------------- -->
                <div class="image-gallery">
                    <img src="black_00001.jpg" alt="Background 1" class="bg-image" data-bg-url="black_00001.jpg">
                    <img src="splash_00226.jpg" alt="Background 2" class="bg-image" data-bg-url="splash_00226.jpg">
                    <img src="pastel_00010.jpg" alt="Background 3" class="bg-image" data-bg-url="pastel_00010.jpg">
                    <img src="white_00006.jpg" alt="Background 4" class="bg-image" data-bg-url="white_00006.jpg">
                    <img src="autumn-leaves_00034.jpg" alt="Background 5" class="bg-image" data-bg-url="autumn-leaves_00034.jpg">
                <!-- ------------------------------------------------------------------------------- -->
                </div>
            </div>
        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {
    const seats = document.querySelectorAll(".seat, .long-seat");
    const seatInfoBubble = document.getElementById("seat-info-bubble");
    const nameForm = document.getElementById("name-form");
    const lastnameInput = document.getElementById("lastname");
    const firstnameInput = document.getElementById("firstname");
    const nameDisplay = document.getElementById("name-display");
    const hamburgerButton = document.getElementById("hamburger-button");
    const hamburgerMenuContent = document.getElementById("hamburger-menu-content");
    const menu1 = document.getElementById("menu1");
    const popup1 = document.getElementById("popup1");
    const closePopupButton1 = document.getElementById("close-popup1");
    const menu2 = document.getElementById("menu2");
    const popup2 = document.getElementById("popup2");
    const closePopupButton2 = document.getElementById("close-popup2");
    const menu3 = document.getElementById("menu3");
    const popup3 = document.getElementById("popup3");
    const closePopupButton3 = document.getElementById("close-popup3");
    const bgImages = document.querySelectorAll(".bg-image");
    let currentName = '';

    seats.forEach(seat => {
        seat.addEventListener("click", function (event) {
            event.stopPropagation(); // 座席クリック時のイベント伝播を防止
            const seatNumber = this.id.split("-")[1];
            const seatState = this.classList.contains("reserved") ? "予約中" :
                this.classList.contains("occupied") ? "利用中" :
                    this.classList.contains("afk") ? "離席中" : "空席";
            const seatName = this.dataset.fullName || this.textContent.trim();
            let infoContent = `<p>座席番号: ${seatNumber}</p>`
                             + `<p>座席状態: ${seatState}</p>`
                             + `<p>予約者氏名: ${seatName}</p>`;

            if (seatState === "空席") {
                infoContent += `<button onclick="reserveSeat(${seatNumber})">予約する</button>`;
            } else if (seatState === "予約中") {
                infoContent += `<button onclick="changeSeatState(${seatNumber}, 'cancel')">キャンセルする</button>`;
                infoContent += `<span style="margin-right: 10px;"></span>`;  
                infoContent += `<button onclick="changeSeatState(${seatNumber}, 'start')">利用開始</button>`;
            } else if (seatState === "利用中") {
                infoContent += `<button onclick="changeSeatState(${seatNumber}, 'cancel')">キャンセルする</button>`;
            }

            seatInfoBubble.innerHTML = infoContent;
            seatInfoBubble.style.display = "block";
            seatInfoBubble.style.left = event.pageX + "px";
            seatInfoBubble.style.top = event.pageY + "px";
        });
    });

    document.addEventListener("click", function () {
        seatInfoBubble.style.display = "none";
    });

    nameForm.addEventListener("submit", function (event) {
        event.preventDefault();
        const lastname = lastnameInput.value;
        const firstname = firstnameInput.value;
        currentName = `${lastname} ${firstname}`;
        nameDisplay.innerHTML = `苗字: ${lastname}　名前: ${firstname}`;
    });

    window.reserveSeat = function (seatNumber) {
        if (currentName.trim() === '') {
            alert("氏名を入力してください。");
            return;
        }
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: `seat_num=${seatNumber}&action=reserve&name=${encodeURIComponent(currentName)}`
        })
            .then(response => response.text())
            .then(data => {
                if (data === "update") {
                    localStorage.setItem('seatUpdate', Date.now()); // ローカルストレージにタイムスタンプを保存
                    location.reload(); // ページをリロードして更新を反映
                } else {
                    alert("予約に失敗しました");
                }
            });
    }

    hamburgerButton.addEventListener("click", function () {
        hamburgerMenuContent.style.display = hamburgerMenuContent.style.display === "block" ? "none" : "block";
    });

    menu1.addEventListener("click", function(event) {
        event.preventDefault();
        popup1.style.display = "block";
    });

    closePopupButton1.addEventListener("click", function() {
        popup1.style.display = "none";
    });

    menu2.addEventListener("click", function(event) {
        event.preventDefault();
        popup2.style.display = "block";
    });

    closePopupButton2.addEventListener("click", function() {
        popup2.style.display = "none";
    });

    menu3.addEventListener("click", function(event) {
        event.preventDefault();
        popup3.style.display = "block";
    });

    closePopupButton3.addEventListener("click", function() {
        popup3.style.display = "none";
    });

    bgImages.forEach(image => {
        image.addEventListener("click", function() {
            const bgUrl = this.getAttribute("data-bg-url");
            document.body.style.backgroundImage = `url(${bgUrl})`;
        });
    });

    // 長輪ポーリングによるDB変更検知
    function checkForUpdates() {
        fetch('check_updates.php')
            .then(response => response.text())
            .then(data => {
                console.log("レスポンス:", data); // レスポンスの内容をログ出力
                if (data === 'update') {
                    console.log("ページをリロードします");
                    location.reload(); // ページをリロードして更新を反映
                }
            })
            .catch(error => console.error('Error:', error))
            .finally(() => {
                setTimeout(checkForUpdates, 1000); // 1秒ごとにチェック
            });
    }

    checkForUpdates();

    window.changeSeatState = function (seatNumber, action) {
        let bodyContent = `seat_num=${seatNumber}&action=${action}`;
        if (action === "start") {
            bodyContent = `seat_num=${seatNumber}&action=update&state=利用中`;
        }
        fetch("", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: bodyContent
        })
            .then(response => response.text())
            .then(data => {
                if (data === "update") {
                    localStorage.setItem('seatUpdate', Date.now()); // ローカルストレージにタイムスタンプを保存
                    location.reload(); // ページをリロードして更新を反映
                } else {
                    alert("操作に失敗しました");
                }
            });
    }

    // ローカルストレージの変更を検知
    window.addEventListener('storage', function(event) {
        if (event.key === 'seatUpdate') {
            location.reload(); // ページをリロードして更新を反映
        }
    });
});

    </script>
</body>

</html>

