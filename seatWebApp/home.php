<?php
require ("conect.php");
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>

  <link rel="stylesheet" href="css.css">

</head>


<body>

  <div id="Home">
    <div class="colorchangeanime_bg">
      <!--右画面-->
      <div class="rightdisplay">
        <!--予約者氏名-->

        <!-- 入力フォーム -->
        <form id="textbox1" action="_name.php" method="post">
          <input id="username" name="username" class="c-form-text" placeholder="氏名を入力してください" maxlength="10"
            oninput="checkLength(this)">
          <br>
          <!--予約するボタン-->
          <button type="submit" id="button" class="buttonOutlineGlow">
            <span class="buttonOutlineGlow_bg"></span>
            <span class="buttonOutlineGlow_item">予約する</span>
          </button>
          <br>
        </form>

        <!-- 全角時の文字数制限 -->
        <!-- <script>

            const inputValue = e.target.value;
            if (inputValue.length > maxLength) {
              e.target.value = inputValue.slice(0, maxLength);
            }

        </script> -->

        <!-- 入力結果を出力 -->
        <p id="output"></p>
        <br>

        <!-- 入力フォーム -->
        <form id="textbox2" action="name2.php" method="post">
          <input id="username2" name="username2" class="c-form-text2" placeholder="氏名を入力してください" maxlength="10">
          <br>
          <!--利用中止ボタン-->
          <button type="submit" id="button3" class="buttonOutlineGradient">
            <span class="buttonOutlineGradient_item">予約/利用中止</span>
          </button>
          <br>
        </form>




        <!--室内情報-->
        <div class="box11">
          <p>
            <!--日付の取得、表示-->
          <p id="dateDisplay"></p>

          <script>
            // 現在の日付を取得
            var today = new Date();

            // 年、月、日を取得
            var year = today.getFullYear();
            // JavaScriptでは月は0から始まるため、1を足して実際の月に変換する
            var month = today.getMonth() + 1;
            var day = today.getDate();

            // 月と日が1桁の場合は前に0を追加する
            if (month < 10) {
              month = '0' + month;
            }
            if (day < 10) {
              day = '0' + day;
            }

            // yyyy/MM/dd形式で日付を表示
            document.getElementById('dateDisplay').textContent = year + '年' + month + '月' + day + '日';
          </script>


          </p>
          <p id="roomname">会議室</p>
          <br>
          <p id="seetcount">座席数：6</p>

          <!-- 空席数カウント -->
          <p id="seetcount">空席数：<span id="vacantCount">6</span></p>

        </div>
      </div>
      <!--座席画面-->
      <div class="field">
        <!-- <div id="square1"></div>  -->

        <?php

        $name = [];
        $sql = "SELECT * FROM seat WHERE sm_num = 1";
        $result = $db->query($sql);
        $result->execute();

        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          // ここでデータを表示する方法を決定
          echo $row["sm_name"] . "<br>";
        }

        // ボタンが押されたときの処理
        if (isset($_POST['afkbtn1'])) {
          // 離席中の状態をデータベースに格納
          $update_sql = "UPDATE seat SET sm_state = '離席中' WHERE sm_num = 1";
          $db->query($update_sql);
          header("Location:./home.php");
          exit();
        }

        if (isset($_POST['afkbtn1_r'])) {
          // 離席中の状態をデータベースに格納
          $update_sql = "UPDATE seat SET sm_state = '利用中' WHERE sm_num = 1";
          $db->query($update_sql);
          header("Location:./home.php");
          exit();
        }

        $sql = "SELECT * FROM seat WHERE sm_num = 1";
        $result = $db->query($sql);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {//DBの情報を取得
          $state = $row['sm_state'];
        }

        if ($state == "空席") {
          echo '<div id="square1" style="background-color: #59b8f3;">空席</div>'; // 空席の場合は水色の背景
        } elseif ($state == "予約中") {
          echo '<div id="square1" style="background-color: purple;">予約中</div>';// 予約中の場合は紫色の背景
        } elseif ($state == "利用中") {
          echo '<div id="square1" style="background-color: #eb2052;">利用中';// 利用中の場合は赤色の背景
          echo '<div style="margin: auto;">';
          // 離席ボタンを表示
          echo '<form method="post">';
          echo '<button type="submit" name="afkbtn1">離席する</button>';
          echo '</form>';
          echo '</div>';
          echo '</div>';
        } elseif ($state == "離席中") {
          echo '<div id="square1" style="background-color: yellow;">離席中';// 利用中の場合は赤色の背景
          echo '<div style="margin: auto;">';
          // 離席解除ボタンを表示
          echo '<form method="post">';
          echo '<button type="submit" name="afkbtn1_r">離席解除</button>';
          echo '</form>';
          echo '</div>';
          echo '</div>';
        }
        ?>


        <!-- <div id="square2">空席</div> -->

        <?php

        // ボタンが押されたときの処理
        if (isset($_POST['afkbtn2'])) {
          // 離席中の状態をデータベースに格納
          $update_sql = "UPDATE seat SET sm_state = '離席中' WHERE sm_num = 2";
          $db->query($update_sql);
        }

        if (isset($_POST['afkbtn2_r'])) {
          // 離席中の状態をデータベースに格納
          $update_sql = "UPDATE seat SET sm_state = '利用中' WHERE sm_num = 2";
          $db->query($update_sql);
        }

        $sql = "SELECT * FROM seat WHERE sm_num = 2";
        $result = $db->query($sql);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {//DBの情報を取得
          $state = $row['sm_state'];
        }

        if ($state == "空席") {
          echo '<div id="square2" style="background-color: #59b8f3;">空席</div>'; // 空席の場合は水色の背景
        } elseif ($state == "予約中") {
          echo '<div id="square2" style="background-color: purple;">予約中</div>';// 予約中の場合は紫色の背景
        } elseif ($state == "利用中") {
          echo '<div id="square2" style="background-color: #eb2052;">利用中';// 利用中の場合は赤色の背景
          echo '<div style="margin: auto;">';
          // 離席ボタンを表示
          echo '<form method="post">';
          echo '<button type="submit" name="afkbtn2">離席する</button>';
          echo '</form>';
          echo '</div>';
          echo '</div>';

        } elseif ($state == "離席中") {
          echo '<div id="square2" style="background-color: yellow;">離席中';// 利用中の場合は赤色の背景
          echo '<div style="margin: auto;">';
          // 離席解除ボタンを表示
          echo '<form method="post">';
          echo '<button type="submit" name="afkbtn2_r">離席解除</button>';
          echo '</form>';
          echo '</div>';
          echo '</div>';
        }
        ?>

        <!-- <div id="square3">
          <form action="home.php" method="post">
          <p><input id="seatAfk" name ="seatAfk" type="submit" value="離席する"></p>
          </form>
        </div> -->

        <?php
        $sql = "SELECT * FROM seat WHERE sm_num = 3";
        $result = $db->query($sql);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {//DBの情報を取得
          $state = $row['sm_state'];
        }

        if ($state == "空席") {
          echo '<div id="square2" style="background-color: #59b8f3;">空席</div>'; // 空席の場合は水色の背景
        } elseif ($state == "予約中") {
          echo '<div id="square2" style="background-color: purple;">予約中</div>';// 予約中の場合は紫色の背景
        } elseif ($state == "利用中") {
          echo '<div id="square2" style="background-color: #eb2052;">利用中</div>';// 利用中の場合は赤色の背景
        } elseif ($state == "離席中") {
          echo '<div id="square2" style="background-color: yellow;">離席中</div>';// 利用中の場合は赤色の背景
        }
        ?>

      </div>
      <div class="ufield">

        <!-- <div id="square4">空席</div> -->

        <?php
        $sql = "SELECT * FROM seat WHERE sm_num = 4";
        $result = $db->query($sql);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {//DBの情報を取得
          $state = $row['sm_state'];
        }

        if ($state == "空席") {
          echo '<div id="square2" style="background-color: #59b8f3;">空席</div>'; // 空席の場合は水色の背景
        } elseif ($state == "予約中") {
          echo '<div id="square2" style="background-color: purple;">予約中</div>';// 予約中の場合は紫色の背景
        } elseif ($state == "利用中") {
          echo '<div id="square2" style="background-color: #eb2052;">利用中</div>';// 利用中の場合は赤色の背景
        } elseif ($state == "離席中") {
          echo '<div id="square2" style="background-color: yellow;">離席中</div>';// 利用中の場合は赤色の背景
        }
        ?>

        <!-- <div id="square5">空席</div> -->

        <?php
        $sql = "SELECT * FROM seat WHERE sm_num = 5";
        $result = $db->query($sql);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {//DBの情報を取得
          $state = $row['sm_state'];
        }

        if ($state == "空席") {
          echo '<div id="square2" style="background-color: #59b8f3;">空席</div>'; // 空席の場合は水色の背景
        } elseif ($state == "予約中") {
          echo '<div id="square2" style="background-color: purple;">予約中</div>';// 予約中の場合は紫色の背景
        } elseif ($state == "利用中") {
          echo '<div id="square2" style="background-color: #eb2052;">利用中</div>';// 利用中の場合は赤色の背景
        } elseif ($state == "離席中") {
          echo '<div id="square2" style="background-color: yellow;">離席中</div>';// 利用中の場合は赤色の背景
        }
        ?>

        <!-- <div id="square6">空席</div> -->

        <?php
        $sql = "SELECT * FROM seat WHERE sm_num = 6";
        $result = $db->query($sql);
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {//DBの情報を取得
          $state = $row['sm_state'];
        }

        if ($state == "空席") {
          echo '<div id="square2" style="background-color: #59b8f3;">空席</div>'; // 空席の場合は水色の背景
        } elseif ($state == "予約中") {
          echo '<div id="square2" style="background-color: purple;">予約中</div>';// 予約中の場合は紫色の背景
        } elseif ($state == "利用中") {
          echo '<div id="square2" style="background-color: #eb2052;">利用中</div>';// 利用中の場合は赤色の背景
        } elseif ($state == "離席中") {
          echo '<div id="square2" style="background-color: yellow;">離席中</div>';// 利用中の場合は赤色の背景
        }
        ?>

      </div>
    </div>
  </div>

  <!-- <script src="yoyakuscript.js"></script> -->
  <!-- <script src="yoyakubutton.js"></script> -->
  <script src="seatcount.js"></script>
  <!-- <script src="afk_script.js"></script> -->


</body>

</html>