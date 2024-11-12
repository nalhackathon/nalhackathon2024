<?php

require("conect.php");

$name2 = $_POST['username2'];
$sql = "SELECT * FROM seat WHERE sm_num = 1";
$result = $db -> query($sql);

while($row = $result -> fetch (PDO::FETCH_ASSOC)) {//DBの情報を取得
     $num = $row['sm_num'];
     $state = $row['sm_state'];
     $name = $row['sm_name'];
     $code = $row['sm_code'];
}

$state_ = "利用中";

try {
    if($name2 == ''){
        header("Location: home.php");
        exit;
    }
    if($name2 == $name){
        $query = "UPDATE seat SET sm_state = :state_ WHERE sm_num = 1";
        $statement = $db->prepare($query);
        $statement->bindParam('state_', $state_);
        header("Location: cancel.php");


    }else{
        $test_alert = "<script type='text/javascript'>alert('正しい氏名を入力してください。');
        window.location.href = 'home.php';</script>";
        echo $test_alert;    
    }


    $statement->execute();
    exit;

        } catch (PDOException $e) {
            echo "データベースエラー: " . $e->getMessage();
            exit();
    }
?>