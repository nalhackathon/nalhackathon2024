<?php

require("conect.php");

$state_ = "予約中";

try {
        $query = "INSERT INTO seat (sm_state) VALUES (:state_)";
        $statement = $db->prepare($query);
           
        $statement->bindParam(':state_', $state_);

        $statement->execute();
        } catch (PDOException $e) {
            echo "データベースエラー: " . $e->getMessage();
            exit();
    }
?>