  
<?php
require ('header.php');


class MenuCategories 
{
    public function __construct(
        
    )
    {
        $this->user_id = $_SESSION['id'];
        $this->categoriesId = $_GET['menu'];
        $this->conn = new PDO("mysql:host=localhost;dbname=sport_news;","root","");
    }
    
    public function outputNewsByCateg()
    {
     
       $sql =  $this->conn->prepare( 
         "SELECT news.id_news , news.title, news.content, news.name_upload, news.date, category.category, category.id_category FROM  news  INNER JOIN category ON  news.id_category = category.id_category WHERE news.id_category = :categoriesId ORDER BY id_news DESC;");

       $sql->bindParam(':categoriesId', $this->categoriesId, PDO::PARAM_INT);
       $sql->execute();
        
         $this->insertComment();
       
     ?>

<div style="margin-top:100px;"></div>

<div class="content" >
    <?php 
        
            while($row_news = $sql->fetch())
            {
            if($row_news['name_upload'] != null){
                
             $this->id_news =  $row_news['id_news'];
               ?>
 

            <div style="margin-top:50px;"></div>
            <div class="container" id="target<?php echo $row_news['id_news'];?>">
                
                <!--Start Update and delete news-->
                <?php   if($_SESSION['user_name'] === 'administrator'){ ?>
                <form  method="POST" action="admin.inc.php?delete=<?php echo $row_news['id_news'];?>" style="float:right;">
                    <input type="hidden" name="id_news" value="<?php echo $row_news['id_news'];?>">
                    <input type="hidden" name="update_id_category" value="<?php echo $row_news['id_category'];?>">
                    <input type="hidden" name="update_category" value="<?php echo $row_news['category'];?>">
                    <input type="hidden" name="update_title" value="<?php echo $row_news['title'];?>">
                    <input type="hidden" name="update_content" value="<?php echo $row_news['content'];?>">
                    <input type="hidden" name="update_image" value="<?php echo $row_news['name_upload'];?>">
                    <input class="print_news_btn"  type="submit" name="update_news" value="Update" formaction="admin.inc.php?update=<?php echo $row_news['id_news'];?>" >
                    <input class="log_out"  type="submit" name="delete_news_from_menu" value="Delete" onclick="return confirm('Are you sure you want to delete this article?');" >
                </form>
                   <?php }?>
                 <!--End Update and delete news-->
                
                
                    <h1 class="category_admin" ><?php echo $row_news['category']; ?></h1>
                    <p class="date"><?php echo "<b>". substr($row_news['date'],0 ,10). '</b> <br>'. substr($row_news['date'],11 ,20);?></p>
                    <h2 ><?php echo $row_news['title']; ?></h2>
                    <div class="new_image" > 
                        <img  src="<?php echo $row_news['name_upload'];?>" >
                    </div>
                    
                    <p class="text" id="text<?php echo $row_news['id_news'];?>"><?php echo $row_news['content'];?></p> 
                    <p id="read_more<?php echo $row_news['id_news'];?>" class="keep_reading">Keep reading...</p>
               
               
                                <?php
                         $this->toogle($row_news['id_news']);
                         $this->countCommentsOnNews($row_news['id_news'], $row_news['id_category']);
                
                    ?>
                     <div id="comment<?php echo $row_news['id_news'];?>" style="display:none;" >
                         
                      <?php
                                // LOOp comments input
                                $this->prepareComment($row_news['id_news']);//iterarate the form
                                $this->outputComment($row_news['id_news']);
                       
                ?>
                     </div>
            
            </div>
    
   
<?php
                    }else{
               echo '';
            }
            }//END WHILE
        ?></div><?php
    }//END outputNewsByCateg()
    
    protected function prepareComment( int $id_news):void
    {
?>
            <div class="input_comment" >
                    <form action="" method="POST">
                        <input type="hidden" name="news_id" value="<?php echo $id_news;?>">
                        <input type="hidden" name="user_id" value="<?php echo $this->user_id;/*user id*/?>">
                        <textarea type="text" name="comment"  placeholder="Input a comment"></textarea>
                        
                        <input class="send_btn" type="submit" name="add_comment" value="Send" >
                    </form>

                </div>
<?php 
    }//END inputComment
    
    protected function insertComment()
    {
        if(isset($_POST['add_comment']))
        {
            if(!empty($_POST['comment']))
            {    
                $comment = htmlentities($_POST['comment']);
                $id_news = $_POST['news_id'];

                $sql = $this->conn->prepare("INSERT INTO comments (id_news, id_user, comment) VALUES (:id_news, :id_user, :comment)");
               try{ $sql->bindParam(":id_news", $id_news);
                    $sql->bindParam(":id_user", $_SESSION['id']);
                    $sql->bindParam(":comment", $comment);
                    $sql->execute();

                   
                   header("Location: menu.php?menu=$this->categoriesId#target$id_news"); 
                  }
                catch(\Exception $e)
                {
                    echo "I can't send this data to the database ",  $e->getMessage();
                }
            }else
            {
              ?><script>alert("Type something before submitting");</script><?php
            }
        }
    }//END insertComment
    
    protected function outputComment($id_news)
    {
        
        $sql = $this->conn->prepare("SELECT comments.id_comment, comments.id_news, comments.comment, comments.date, user.id, user.surname, user.user_name, news.id_news FROM ((comments INNER JOIN user ON comments.id_user = user.id) INNER JOIN news ON comments.id_news = news.id_news)
        WHERE comments.id_news = :idNews ORDER BY id_comment DESC;" );
        $sql->bindParam(':idNews', $id_news, PDO::PARAM_INT);
        $sql->execute();
        while($comm_row = $sql->fetch())
        {
?>
  <?php

            ?> 
                <div style="margin-top:20px;"></div>
                <div  class="comment"  >
                   
                
                    <h3><?php echo $comm_row['user_name'];?></h3>
                    <p class="date"><?php echo "<b>". substr($comm_row['date'],0 ,10). '</b> <br>'; ?></p>
                    <p class="comment_content"><?php echo $comm_row['comment']; ?></p>
                   
        <?php if($comm_row['id'] == $this->user_id || $_SESSION['user_name'] === 'administrator'){ ?>
         
                    <form style="text-align:right;"  method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" >
                          <input type="hidden" name="id_news" value="<?php echo $comm_row['id_news'];?>">
                          <input type="hidden" name="id_category" value="<?php echo $this->categoriesId;?>">
                          <input type="hidden" name="date" value="<?php echo substr($comm_row['date'],0 ,10); ?>">
                          <input type="hidden" name="id_comment" value="<?php echo $comm_row['id_comment'];?>">
                          <input type="hidden" name="comment" value="<?php echo $comm_row['comment'];?>">
                        <!--Give priority to the administrator just to delete this comment, but not to change it-->
                     <?php if($comm_row['id'] == $this->user_id ){ ?>

                          <input class="edit_coment" type="submit" name="edit_comment" value="Edit" formaction="update.delete.php">
                    <?php }//END if?>
                          <input class="delete_comment" type="submit" name="delete_comment" value="Delete" onclick="return confirm('Are you sure you want to delete this comment?');" formaction="update.delete.php">
                    </form>
                    
        <?php }//END if?>
                </div>
<?php
        }//END WHILE
        
    }//END outputComment()
 
    public function countCommentsOnNews($inNews)
    {
        $sql = "SELECT COUNT(id_comment) FROM sport_news.comments WHERE id_news = $inNews;";
        $res = $this->conn->query($sql);
        $count = $res->fetchColumn();

            echo "<p id='see".$inNews."'>Comments (" .  $count . ").</p>";
      
    }
  public function toogle($inNews)
  {
      ?>
          <script > 
              $(document).ready(function(){
                    $("#see<?php echo $inNews;?>").click(function(){
                    $("#comment<?php echo $inNews;?>").toggle(500); //poate sa aibe ca parametrii stringuri "fast","medium" si "slow"
                    });
                });
              
              
            $(document).ready(function(){
              $("#read_more<?php echo $inNews;?>").click(function(){
                $("#text<?php echo $inNews;?>").toggle(1000);
              });
            });
              
              function myFunction() {
                  var x = document.getElementById("read_more<?php echo $inNews;?>");
                  if (x.innerHTML === "Keep reading...") {
                    x.innerHTML = "Close.";
                  } else {
                    x.innerHTML = "Keep reading...";
                  }
                }
        </script> 
 

    <?php
  }
    
}//END CLASS MenuCategories

$outpoutNews = new MenuCategories ();
$outpoutNews->outputNewsByCateg();

//$outpoutNews->countCommentsOnNews();
  

        


require('footer.php');
?>











