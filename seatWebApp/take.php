<?php
$name =[];
$state = [];
$name = [];
$code = [];

try {
    $db = new PDO('mysql:dbname=hironaka;host=127.0.0.1;charset=utf8mb4', 'naladmin', 'naladmin');
}   catch (PDOException $e) {
    echo "データベース接続エラー :".$e->getMessage();
}
$sql = "SELECT * FROM seat WHERE sm_num = 1";
$result = $db -> query($sql);
$result -> execute();


  // データが存在する場合、それを表示
  while($row = $result -> fetch (PDO::FETCH_ASSOC)) {
      // ここでデータを表示する方法を決定し、例えばechoを使用してHTML形式で表示することができます
      echo  "氏名: " . $row["sm_name"]. "<br>";
  }


?>