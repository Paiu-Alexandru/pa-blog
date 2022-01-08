<?php
require('header.php');
if(isset($_POST['edit_comment'])){ 
?>

     <h2 style="text-align:center; ">Edit your message from / <?php echo $_POST['date'];?></h2>
                <div class="input_comment" >
                    <form  action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
                        <input type="hidden" name="id_news" value="<?php echo $_POST['id_news'];?>">
                        <input type="hidden" name="id_category" value="<?php echo $_POST['id_category'];?>">
                        <input type="hidden" name="id_comment" value="<?php echo $_POST['id_comment'];?>">
                        <textarea style="padding:15px;" name="comment" rows="10" cols="142" ><?php echo $_POST['comment'];?> </textarea> <br>
                        <input style="width:120px;" class="edit_coment" type="submit" name="update_comment" value="Send">
                        <input style="width:120px;" class="delete_comment" type="submit" name="close" value="Close" formaction="<?php echo "menu.php?menu=".$_POST['id_category'];?>">
                    </form>
                </div>
<?php       
}// End if $_POST['edit_comment']


    class Update{
        public function __construct(
            $conn = '', 
            int $id_category =0)
        {
            $this->conn = Db_conn::conection();
            $this->id_category = $_POST['id_category'];

        }
        
        public function updateComment()
        {
           $comment = htmlentities($_POST['comment']);
           $id_comment = $_POST['id_comment'];
           $id_news = $_POST['id_news'];
           $sql = $this->conn->prepare("UPDATE sport_news.comments SET comment = :comment WHERE comments.id_comment = :id_comment;");
                try{
                   $sql->bindParam(':comment', $comment);
                   $sql->bindParam(':id_comment', $id_comment); 
                   $sql->execute();

                   header("Location: menu.php?menu=$this->id_category#target$id_news");
                    
                }
                catch(Exception $e)
                {
                    echo "I can't update this comment",  $e->getMessage();
                }
        } 
        
        public function deleteComment()
          {

                  $id_comment = $_POST['id_comment'];

                  $sql = $this->conn->prepare("DELETE FROM sport_news.comments WHERE comments.id_comment = :id_comment;");
                      try{
                          $sql->bindParam(":id_comment", $id_comment);
                          $sql->execute();
                           header("Location: menu.php?menu=".$this->id_category."#target".$_POST['id_news']);
                          

                      }
                      catch( Exception $e){
                           echo 'Error',  $e->getMessage();
                      }

          }
    }//End update class

$upd = new Update();

    if(isset($_POST['update_comment']))
    {
        $upd->updateComment();
    }

    if(isset($_POST['delete_comment']))
    {
        $upd->deleteComment();
    }





  
?>