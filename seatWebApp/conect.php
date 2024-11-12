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
 
$sql = 'select * from seat';//DBのテーブルを指定
$result = $db -> query($sql);
$result -> execute();

while($row = $result -> fetch (PDO::FETCH_ASSOC)) {//DBの情報を取得
     $num = $row['sm_num'];
     $state = $row['sm_state'];
     $name = $row['sm_name'];
     $code = $row['sm_code'];
}

?>