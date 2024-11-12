<?php

require("conect.php");

$state_ = "離席中";

try {

    $query = "UPDATE seat SET sm_state = :state_ WHERE sm_num = 1";
    $statement = $db->prepare($query);
       
    $statement->bindParam(':state_', $state_);

    $statement->execute();
    } catch (PDOException $e) {
        echo "データベースエラー: " . $e->getMessage();
        exit();
}
?>

<?php
          function afk2(){
            global $state;
            global $db;
            $state4_ = "離席中";
            $state2_ = "利用中";
          try {
          if($state != $state4_){
            $query = "UPDATE seat SET sm_state = :state_ WHERE sm_num = 1";
            $statement = $db->prepare($query);
            $statement->bindParam(':state_', $state4_);
            
          }else{

            $query = "UPDATE seat SET sm_state = :state_ WHERE sm_num = 1";
            $statement = $db->prepare($query);
         
            $statement->bindParam(':state_', $state2_);

          }

          $statement->execute();
          } catch (PDOException $e) {
          echo "データベースエラー: " . $e->getMessage();
          exit();
          }
          }
          if(isset($_POST['seatAfk'])){
            echo "console.log('test')";
            afk2();
            echo '<script>', 'afk();', '</script>';
            
          }
          ?> 