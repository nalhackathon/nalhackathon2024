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
 
$sql = "SELECT * FROM seat WHERE sm_num = 6";
$result = $db -> query($sql);

while($row = $result -> fetch (PDO::FETCH_ASSOC)) {//DBの情報を取得
     $num = $row['sm_num'];
     $state = $row['sm_state'];
     $name = $row['sm_name'];
     $code = $row['sm_code'];
}

    $pass1 = $_POST['num1'];
    $pass2 = $_POST['num2'];
    $pass3 = $_POST['num3'];
    $passcode = $pass1 . " " . $pass2 . " " . $pass3; //取得した文字列を一列横並びに格納
    $state_ = "空席";

try {
    
    echo $passcode;
       if($code == $passcode){
        $query = "UPDATE seat SET sm_state = :state_, sm_name = null, sm_code = null WHERE sm_num = 6";
        $statement = $db->prepare($query);

        $statement->bindParam(':state_', $state_);

       }else{
        $test_alert = "<script type='text/javascript'>alert('正しいパスコードを入力してください。');
        window.location.href = 'cancel.php';</script>";
        echo $test_alert;
        
       }

        $statement->execute();

        header("Location:home.php");
        exit;
        } catch (PDOException $e) {
            echo "データベースエラー: " . $e->getMessage();
            exit();
    }
?>