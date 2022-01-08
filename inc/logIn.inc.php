<?php
session_start();
require ('../conn/conn.php');


class LogIn{
      public function __construct(
        $conn = "",
        string $user_name = '',
        string $password = '',
        ){
          $this->conn =       Db_conn::conection();
          $this->user_name =  filter_var(trim($_POST['user_name']),FILTER_SANITIZE_STRING);
          $this->password =   filter_var(md5(trim($_POST['password']),1),FILTER_SANITIZE_STRING);
          
    }

    public function logIn()
    {
          
            $conn = Db_conn::conection();
            $sql = $this->conn->prepare('SELECT * FROM sport_news.user WHERE user_name = :user_name AND password = :password');
            $sql->bindParam(':user_name', $this->user_name);
            $sql->bindParam(':password', $this->password);
            $sql->execute();
                    while($row = $sql->fetch()){
                    $_SESSION['id'] = $row['id'] ;
                    $_SESSION['name'] =  $row['name'] ;
                    $_SESSION['surname'] =  $row['surname'] ;
                    $_SESSION['user_name'] =  $row['user_name'] ;
                    $_SESSION['password'] = $row['password'] ;
                    }
           if($_SESSION['user_name'] == $this->user_name && $_SESSION['password'] == $this->password){
                header('Location: ../view/home.php?news=all');
                  //echo "god job";
            }
        else{
                header('Location: ../index.php?error=false');
                  //echo "user not find";
            }
     
            
       
    }
}


   if(isset($_POST['user_name']) && !empty($_POST['user_name'])){
           if(isset($_POST['password']) && !empty($_POST['password'])){
                $log = new LogIn();
                $log->logIn();
            } else{
                header('Location: ../index.php?error=falsepass');
                
                }
        } else
            {
               header('Location: ../index.php?error=falseuser');
               //echo "username missing";
           }


?>