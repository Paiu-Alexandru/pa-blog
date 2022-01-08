
<?php 
const DB_DRIVER = "mysql";
const DB_HOST = "localhost";
const DB_NAME = "sport_news";
const DB_LOGIN = "root";
const DB_PASS = "";

     class Db_conn{
         protected static $pdo;
         
     public function __construct(){}
         
                    public static function conection(){
                      $dsn = DB_DRIVER.':host'. DB_HOST . ';dbname='.DB_NAME;
                      try{
                          //Connect to database.
                          self::$pdo = new PDO ($dsn, DB_LOGIN, DB_PASS);
                          self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                          return self::$pdo;
                      }
                      catch(PDOException $e){
                          die ("ERROR no connection: ". $e->getMessage());
                      }
                  }
         
         
         }
 


?>