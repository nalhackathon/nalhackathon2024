<?php

require("conect.php");

$name = $_POST['username'];

try {
    if($name == ''){
        header("Location: home.php");
        exit;
    }
        $query = "UPDATE seat SET sm_name = :name_ WHERE sm_num = 1";
        $statement = $db->prepare($query);
        
        $statement->bindParam(':name_', $name);
        $statement->execute();

        header("Location: yoyaku.php");
        exit;

        } catch (PDOException $e) {
            echo "データベースエラー: " . $e->getMessage();
            exit();
    }
?>