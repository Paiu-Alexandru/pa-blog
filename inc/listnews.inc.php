<?php



class News{
   public function construct( ){}
   /* public function selectNews(){
         $conn = Db_conn::conection();
         $stmt = $conn->query("SELECT * FROM sport_news.news ORDER BY id_news DESC LIMIT 5 ");//Must type and database name / SELECT * FROM sport_news.user
         
         while($row = $stmt->fetch()){
             $news = '<div class="container_login_news"> <div class="login_news">';
             $news .= '<h2>' . $row['title'] . '</h2><br>';
             $news .= '<div class="news_img"> <img src="data:image;base64,'.base64_encode($row['image']).'" alt="Image" style="max-width:100%;height:auto;"> </div> <br>';
             $news .='<div class="content">'. $row['content']. '</div></div>  </div>';
             
             echo $news;
         } 
        $conn=die();
    }
    */
    public function outputNews($newsNr)
    {
        $conn = new PDO("mysql:host=localhost;dbname=sport_news;","root","");
        $sql = $conn->prepare("SELECT news.id_news , news.title, news.content, news.name_upload, news.date, category.category, category.id_category FROM  sport_news.news  INNER JOIN category ON  news.id_category = category.id_category  ORDER BY id_news DESC LIMIT $newsNr;");
        
        $sql->execute();
                
         while($row_news = $sql->fetch()){    
?><div style="margin-top:30px;" ></div>
             <div class="container" >

                <?php if(session_status() === PHP_SESSION_ACTIVE){ $this->ifSessionAdmin($row_news['id_news']);}?>

                <h1 class="category_admin" ><?php echo $row_news['category']; ?></h1>
                <p class="date"><?php echo "<b>". substr($row_news['date'],0 ,10). '</b> <br>'. substr($row_news['date'],11 ,20);?></p>
                <h2><?php echo $row_news['title']; ?></h2>
                 
                 
                 
               <div class="new_image" > 
                <img  src="<?php if(session_status() === PHP_SESSION_ACTIVE){ echo $row_news['name_upload'];}else{echo substr($row_news['name_upload'],3);}?>" >
                </div>
                 
                <p class="text"><?php echo $row_news['content'];?></p>
            </div>
            
<?php   
         }
            
            
         
    }//End outputNews()
    
    public function ifSessionAdmin($id_news){
        if($_SESSION['user_name'] === 'administrator' ){
        ?>
                <h2 class="hidden" style="color:red;">Check your last post</h2>
                        <form  method="POST" style="float:right;">
                            <input type="hidden" name="id_news" value="<?php echo $id_news;?>">
                            <input class="hidden_edit"  type="submit" name="hidden" value="Hidden" formaction="?hidden">
                            <input class="log_out"  type="submit" name="delete_news" value="Delete" onclick="return confirm('Are you sure you want to delete this comment?');" formaction="../view/admin.inc.php">
                        </form>
        <?php
        }
    }
    
}
$news = new News();

if(isset($_GET['news']) && $_GET['news'] == 'all' || isset($_GET['succes']) && $_GET['succes'] == 'deleted'){
                     
                 echo   " <style>
                         .hidden_edit{display: none;}
                         .hidden{display: none;}
                     </style>";
                    }









?>
