<?php

require("conect.php");

$pass1 = $_POST['num1'];
$pass2 = $_POST['num2'];
$pass3 = $_POST['num3'];
$passcode = $pass1 . " " . $pass2 . " " . $pass3; //取得した文字列を一列横並びに格納
$state_ = "予約中";

try {
        $query = "UPDATE seat SET sm_state = :state_, sm_code = :passcode_ WHERE sm_num = 3";
 
        $statement = $db->prepare($query);
        $statement->bindParam(':passcode_', $passcode);
        $statement->bindParam(':state_', $state_);
        $statement->execute();

        
        header("Location: yoyaku.php");
        exit;
        } catch (PDOException $e) {
            echo "データベースエラー: " . $e->getMessage();
            exit();
    }
?>