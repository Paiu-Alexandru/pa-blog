

<?php

class Menu{
     public function __construct($conn = "")
     {
          $this->conn = Db_conn::conection();
          
    }
    public function menuData(){
        $sql = $this->conn->prepare("SELECT * FROM sport_news.category ORDER BY id_category");
        $sql->execute();
        
        while($row = $sql->fetch()){
            echo '
                  <a  onclick="closeNav()" href="menu.php?menu='.$row['id_category'].'">'.$row['category'].'</a> ';
        }
        echo '<a  onclick="closeNav()" href="home.php?news=all">All news </a>';
    }
}

//$menu = new Menu();






?>