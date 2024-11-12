<?php

   try {
  $db = new PDO('mysql:dbname=hironaka;host=127.0.0.1;charset=utf8mb4', 'naladmin', 'naladmin');
  }   catch (PDOException $e) {
   echo "データベース接続エラー :".$e->getMessage();
 }
   ?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>キャンセル</title>
  <link rel="stylesheet" href="css.css">
  <link rel="stylesheet" href="popup.css">

</head>


<body>
  <div id="Home">
    <div class="colorchangeanime_bg_red">

      <!--右画面-->  
    <div class="rightdisplay">
        <!--予約者氏名-->
        <br>
        <h2 id = "name1">予約者氏名
          <?php

          $name = [];
          $sql = "SELECT * FROM seat WHERE sm_num = 1";
          $result = $db -> query($sql);
          $result -> execute();
  
          while($row = $result -> fetch (PDO::FETCH_ASSOC)) {
          // ここでデータを表示する方法を決定し
          echo  "氏名: " . $row["sm_name"]. "<br>";
          }
          ?>

        </h2>
          
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

        <p id = "roomname">会議室</p>
        <br>
        <p id = "seetcount" >座席数：6</p>
        <p id = "seetcount" >空席数：<span id="vacantCount">6</span></p>

    </div>
    </div>

    <!--ホームボタン-->  
    <div class="button008">
      <a href="home.php">ホームへ</a>
    </div>
      <!--座席画面--> 
      <div class="field">
        <!-- 座席1 -->
      <div>
      
      <?php
        $sql = "SELECT * FROM seat WHERE sm_num = 1";
        $result = $db -> query($sql);
        while($row = $result -> fetch (PDO::FETCH_ASSOC)) {//DBの情報を取得
        $state = $row['sm_state'];
        }

        if($state == "空席"){   
          echo '<div id="square1" style="background-color: #59b8f3;">空席</div>';// 空席の場合は水色の背景
        }elseif($state == "予約中"){
          echo '<div id="square1" style="background-color: purple;">予約中';// 予約中の場合は紫色の背景
          echo '<div style="margin: auto;">';
          echo '<button id="sq1btn_c">キャンセル</button>';
          echo '</div>';
          echo '</div>';
        }elseif($state == "利用中"){
          echo '<div id="square1" style="background-color: #eb2052;">利用中';// 利用中の場合は赤色の背景
          echo '<div style="margin: auto;">';
          echo '<button id="sq1btn_c">キャンセル</button>';
          echo '</div>';
          echo '</div>';
        }elseif($state == "離席中"){
          echo '<div id="square1" style="background-color: yellow;">離席中</div>';// 利用中の場合は赤色の背景
        }
        ?>
      <div id="popup-wrapper1">
      <div id="popup-inside1">
      <div id="close1">x</div>
      <div id="message1">
      <h5>パスコード設定</h5>
      <p>パスコードを設定してください</p>

      <form id = "numbox4" action="_passCode_c.php" method="post">
      <input id="num1" name="num1" type="number" max="5" min = "1" required>
      <input id="num2" name="num2" type="number" max="5" min = "1" required>
      <input id="num3" name="num3" type="number" max="5" min = "1" required>
      <button type="submit" >決定</button></form>
      </div>
      </div>
      </div>

      </div>

        <!-- 座席２ -->
        <!-- <div id="square2">空席</div> -->
        <div>
      
        <?php
        $sql = "SELECT * FROM seat WHERE sm_num = 2";
        $result = $db -> query($sql);
        while($row = $result -> fetch (PDO::FETCH_ASSOC)) {//DBの情報を取得
        $state = $row['sm_state'];
        }

        if($state == "空席"){   
          echo '<div id="square2" style="background-color: #59b8f3;">空席</div>';// 空席の場合は水色の背景
        }elseif($state == "予約中"){
          echo '<div id="square2" style="background-color: purple;">予約中';// 予約中の場合は紫色の背景
          echo '<div style="margin: auto;">';
          echo '<button id="sq2btn_c">キャンセル</button>';
          echo '</div>';
          echo '</div>';
        }elseif($state == "利用中"){
          echo '<div id="square2" style="background-color: #eb2052;">利用中';// 利用中の場合は赤色の背景
          echo '<div style="margin: auto;">';
          echo '<button id="sq2btn_c">キャンセル</button>';
          echo '</div>';
          echo '</div>';
        }elseif($state == "離席中"){
          echo '<div id="square2" style="background-color: yellow;">離席中</div>';// 利用中の場合は赤色の背景
        }
        ?>
      <div id="popup-wrapper2c">
      <div id="popup-inside2c">
      <div id="close2c">x</div>
      <div id="message2c">
      <h5>パスコード設定</h5>
      <p>パスコードを設定してください</p>

      <form id = "numbox4" action="_passCode2_c.php" method="post">
      <input id="num1" name="num1" type="number" max="5" min = "1" required>
      <input id="num2" name="num2" type="number" max="5" min = "1" required>
      <input id="num3" name="num3" type="number" max="5" min = "1" required>
      <button type="submit" >決定</button></form>
      </div>
      </div>
      </div>

      </div>
        <!-- 座席３ -->
        <!-- <div id="square3">利用中</div> -->
        <div>
        <?php
        $sql = "SELECT * FROM seat WHERE sm_num = 3";
        $result = $db -> query($sql);
        while($row = $result -> fetch (PDO::FETCH_ASSOC)) {//DBの情報を取得
        $state = $row['sm_state'];
        }

        if($state == "空席"){
          echo '<div id="square3" style="background-color: #59b8f3;">空席</div>';// 空席の場合は水色の背景
        }elseif($state == "予約中"){
          echo '<div id="square3" style="background-color: purple;">予約中';// 予約中の場合は紫色の背景
          echo '<div style="margin: auto;">';
          echo '<button id="sq3btn_c">キャンセル</button>';
          echo '</div>';
          echo '</div>';
        }elseif($state == "利用中"){
          echo '<div id="square3" style="background-color: #eb2052;">離席中';// 利用中の場合は赤色の背景
          echo '<div style="margin: auto;">';
          echo '<button id="sq3btn_c">キャンセル</button>';
          echo '</div>';
          echo '</div>';
        }elseif($state == "離席中"){
          echo '<div id="square3" style="background-color: yellow;">離席中</div>';// 利用中の場合は赤色の背景
        }
        ?>

      <div id="popup-wrapper3c">
      <div id="popup-inside3c">
      <div id="close3c">x</div>
      <div id="message3c">
      <h5>パスコード設定</h5>
      <p>パスコードを設定してください</p>

      <form id = "numbox4" action="_passCode3_c.php" method="post">
      <input id="num1" name="num1" type="number" max="5" min = "1" required>
      <input id="num2" name="num2" type="number" max="5" min = "1" required>
      <input id="num3" name="num3" type="number" max="5" min = "1" required>
      <button type="submit" >決定</button></form>
      </div>
 
      </div>
      </div>
      </div>
    </div>
      
    
    <div class="ufield">
        <!-- 座席４ -->
        <!-- <div id="square4">空席</div> -->
        <div>
        <?php
        $sql = "SELECT * FROM seat WHERE sm_num = 4";
        $result = $db -> query($sql);
        while($row = $result -> fetch (PDO::FETCH_ASSOC)) {//DBの情報を取得
        $state = $row['sm_state'];
        }

        if($state == "空席"){
          echo '<div id="square4" style="background-color: #59b8f3;">空席</div>';// 空席の場合は水色の背景
        }elseif($state == "予約中"){
          echo '<div id="square4" style="background-color: purple;">予約中';// 予約中の場合は紫色の背景
          echo '<div style="margin: auto;">';
          echo '<button id="sq4btn_c">キャンセル</button>';
          echo '</div>';
          echo '</div>';
        }elseif($state == "利用中"){
          echo '<div id="square4" style="background-color: #eb2052;">利用中';// 利用中の場合は赤色の背景
          echo '<div style="margin: auto;">';
          echo '<button id="sq4btn_c">キャンセル</button>';
          echo '</div>';
          echo '</div>';
        }elseif($state == "離席中"){
          echo '<div id="square4" style="background-color: yellow;">離席中</div>';// 利用中の場合は赤色の背景
        }
        ?>
        
        <div id="popup-wrapper4c">
        <div id="popup-inside4c">
        <div id="close4c">x</div>
        <div id="message4c">
        <h5>パスコード設定</h5>
        <p>パスコードを設定してください</p>

        <form id = "numbox4" action="_passCode4_c.php" method="post">
        <input id="num1" name="num1" type="number" max="5" min = "1" required>
        <input id="num2" name="num2" type="number" max="5" min = "1" required>
        <input id="num3" name="num3" type="number" max="5" min = "1" required>
        <button type="submit" >決定</button></form>
        </div>
        </div>
        </div>
        </div>

      <!-- 座席５ -->
      <!-- <div id="square5"></div> -->
      <div>
      <?php
      $sql = "SELECT * FROM seat WHERE sm_num = 5";
      $result = $db -> query($sql);
      while($row = $result -> fetch (PDO::FETCH_ASSOC)) {//DBの情報を取得
      $state = $row['sm_state'];
      }

      if($state == "空席"){
        echo '<div id="square5" style="background-color: #59b8f3;">空席</div>';// 空席の場合は水色の背景
      }elseif($state == "予約中"){
        echo '<div id="square5" style="background-color: purple;">予約中';// 予約中の場合は紫色の背景
        echo '<div style="margin: auto;">';
        echo '<button id="sq5btn_c">キャンセル</button>';
        echo '</div>';
        echo '</div>';
      }elseif($state == "利用中"){
        echo '<div id="square5" style="background-color: #eb2052;">利用中';// 利用中の場合は赤色の背景
        echo '<div style="margin: auto;">';
        echo '<button id="sq5btn_c">キャンセル</button>';
        echo '</div>';
        echo '</div>';
      }elseif($state == "離席中"){
        echo '<div id="square5" style="background-color: yellow;">離席中</div>';// 利用中の場合は赤色の背景
      }
      ?>

      <div id="popup-wrapper5c">
      <div id="popup-inside5c">
      <div id="close5c">x</div>
      <div id="message5c">
      <h5>パスコード設定</h5>
      <p>パスコードを設定してください</p>

      <form id = "numbox4" action="_passCode5_c.php" method="post">
      <input id="num1" name="num1" type="number" max="5" min = "1" required>
      <input id="num2" name="num2" type="number" max="5" min = "1" required>
      <input id="num3" name="num3" type="number" max="5" min = "1" required>
      <button type="submit" >決定</button></form>
      </div>
      </div>
      </div>
      </div>

        <!-- 座席６ -->
        <!-- <div id="square6"></div> -->
        <div>
        <?php
        $sql = "SELECT * FROM seat WHERE sm_num = 6";
        $result = $db -> query($sql);
        while($row = $result -> fetch (PDO::FETCH_ASSOC)) {//DBの情報を取得
        $state = $row['sm_state'];
        }

        if($state == "空席"){
            echo '<div id="square6" style="background-color: #59b8f3;">空席</div>';// 空席の場合は水色の背景
        }elseif($state == "予約中"){
          echo '<div id="square6" style="background-color: purple;">予約中';// 予約中の場合は紫色の背景
          echo '<div style="margin: auto;">';
          echo '<button id="sq5btn_c">キャンセル</button>';
          echo '</div>';
          echo '</div>';
        }elseif($state == "利用中"){
          echo '<div id="square6" style="background-color: #eb2052;">利用中';// 利用中の場合は赤色の背景
          echo '<div style="margin: auto;">';
          echo '<button id="sq5btn_c">キャンセル</button>';
          echo '</div>';
          echo '</div>';
        }elseif($state == "離席中"){
          echo '<div id="square6" style="background-color: yellow;">離席中</div>';// 利用中の場合は赤色の背景
        }
        ?>
        <div id="popup-wrapper6c">
        <div id="popup-inside6c">
        <div id="close6c">x</div>
        <div id="message6c">
        <h5>パスコード設定</h5>
        <p>パスコードを設定してください</p>

        <form id = "numbox4" action="_passCode6_c.php" method="post">
        <input id="num1" name="num1" type="number" max="5" min = "1" required>
        <input id="num2" name="num2" type="number" max="5" min = "1" required>
        <input id="num3" name="num3" type="number" max="5" min = "1" required>
        <button type="submit" >決定</button></form>
        </div>
        </div>
        </div>
        </div>
    </div>
</div>
</div>

  <!-- <script src="yoyakuscript.js"></script> -->
  <script src="yoyakubutton.js"></script>
  <script src="seatcount.js"></script>
  <script src="seatbutton.js"></script>
</body>
</html>