<?php
session_start();
require ('../conn/conn.php');
require('listnews.inc.php');




class CreateAcount{
    
    public function __construct(
        $conn='',
        string $name ='',
        string $surname = '',
        string $user_name = '',
        string $password = '',
        string $e_mail = ''
        ){
        
          $this->conn =       Db_conn::conection();
          $this->name =       filter_var(trim(ucwords($_POST['name'])),FILTER_SANITIZE_STRING);
          $this->surname =    filter_var(trim(ucwords($_POST['surname'])),FILTER_SANITIZE_STRING);
          $this->user_name =  filter_var(trim($_POST['user_name']),FILTER_SANITIZE_STRING);
          $this->password =   filter_var(md5(trim($_POST['password']),1),FILTER_SANITIZE_STRING);
          $this->e_mail =     filter_var(trim($_POST['e_mail']),FILTER_SANITIZE_STRING);
    }//close constructor
    
    public function checkUserName()
    {
             
        $check = $this->conn->prepare('SELECT * FROM sport_news.user WHERE user_name = :user_name');
        $check->bindParam(':user_name', $this->user_name);
        $check->execute();
                while($user_row = $check->fetch()){
                    $user_name = $user_row['user_name'];
                    $user_pass = $user_row['password'];
                    $user_id = $user_row['id'];
                }
        if($user_id > 0){
           
             header('Location: ../view/signin.php?error=exists');
        }else{
              $this->inserUser();
              $this->createSession();
        }     
    }
    
    
    protected function inserUser():void
    {
                 
             $sql = $this->conn->prepare('INSERT INTO sport_news.user (name, surname, user_name, password, e_mail) VALUES (:name, :surname, :user_name, :password, :e_mail)');
                    $sql->bindParam(':name', $this->name);
                    $sql->bindParam(':surname', $this->surname);
                    $sql->bindParam(':user_name', $this->user_name);
                    $sql->bindParam(':password', $this->password);
                    $sql->bindParam(':e_mail', $this->e_mail);
                    $sql->execute();
                  
             header('Location: ../view/home.php');
                           
    }//end insertUser(); method
    
    protected function createSession()
    {
        
        $sql = $this->conn->prepare('SELECT * FROM sport_news.user');
        
        $sql->execute();
       
                while($row = $sql->fetch()){
                    $_SESSION['id'] = $row['id'] ;
                    $_SESSION['name'] =  $row['name'] ;
                    $_SESSION['surname'] =  $row['surname'] ;
                    $_SESSION['user_name'] =  $row['user_name'] ;
                }    
    }
       
}


if(!empty($_POST['name'])  && !empty($_POST['surname']) && !empty($_POST['user_name']) && !empty($_POST['password']) && !empty($_POST['e_mail'])){
   
   //call result inserted or not
   $insertUser = new CreateAcount();
   $insertUser->checkUserName();
            
 } else{
                    header('Location: ../view/signin.php?error');
                }   














?>