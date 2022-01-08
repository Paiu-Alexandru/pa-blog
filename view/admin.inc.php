
<?php
require('header.php');



class Admin 
{
    public function __construct(
        $conn = "",
        
    )
     {
         
            
            $this->conn = Db_conn::conection();
          
    }
    public function category()
    {
        $sql = $this->conn->prepare("SELECT * FROM sport_news.category ORDER BY id_category");
        $sql->execute();
        
        
      
        while($row = $sql->fetch()){
            if(isset($_POST['update_category']) && isset($_POST['update_id_category']) ) {
                //I check if the value is transmitted and if it matches the category_id and set it as the default value
                   if($_POST['update_category'] == $row['category'] && $_POST['update_id_category'] == $row['id_category']) {
                       
                        echo ' <input id="'.$row['category'].'" type="radio"  name="type_news" value="'.$row['id_category'].'" checked>
                              <label for="'.$row['category'].'">'.$row['category'].'</label>';
                    } else{
                            echo ' <input id="'.$row['category'].'" type="radio"  name="type_news" value="'.$row['id_category'].'" >
                                 <label for="'.$row['category'].'">'.$row['category'].'</label>';
                          }
            }else{
                 echo ' <input id="'.$row['category'].'" type="radio"  name="type_news" value="'.$row['id_category'].'" >
                      <label for="'.$row['category'].'">'.$row['category'].'</label>';
                 }
          
        }//End While  
    }//END category()
    
    public function insertNewsData()
    {
       
        
        $update_img = $this->conn->prepare("UPDATE sport_news.news SET id_category = :id_category, title = :title, content = :content, image_upload = :image_upload, name_upload = :name_upload
        WHERE news.id_news = :id_news;");
        $update_img->bindParam(':id_news', $_POST['id_news']);// Post value cume from this form
        $update_img->bindParam(':id_category', $_POST['type_news']);
        $update_img->bindParam(':title', $title);
        $update_img->bindParam(':content', $content);
        $update_img->bindParam(':image_upload', $_FILES['upload_image']['tmp_name']);
        $update_img->bindParam(':name_upload', $target_file);
        
        
        $update_content = $this->conn->prepare("UPDATE sport_news.news SET id_category = :id_category, title = :title, content = :content
        WHERE news.id_news = :id_news;");
        $update_content->bindParam(':id_category', $_POST['type_news']);
        $update_content->bindParam(':id_news', $_POST['id_news']);// Post value cume from this form
        $update_content->bindParam(':title', $title);
        $update_content->bindParam(':content', $content);
        
        
        
        $title = htmlentities($_POST['title']);
        $content = htmlentities($_POST['content']);
        
        $sql = $this->conn->prepare("INSERT INTO sport_news.news (`id_category`, `title`, `content`, `image_upload`, `name_upload`)
        VALUES(:id_category, :title, :content, :image_upload, :name_upload)");
        $sql->bindParam(':id_category', $_POST['type_news']);
        $sql->bindParam(':title', $title);
        $sql->bindParam(':content', $content);
        $sql->bindParam(':image_upload', $_FILES['upload_image']['tmp_name']);
        $sql->bindParam(':name_upload', $target_file);
        
        if(empty($_POST['type_news'])){
            echo "Category type is missing.<br> ";
        }
        if(empty($title)){
            echo "Missing title.<br>";
        }
        
        
        if(empty($content)){
            echo "Missing content.<br>";
        }
        if(empty($_FILES['upload_image']['name'] )){
            echo "Pick a image.<br>";
        }
        
        
        
 
        if(!empty($_FILES['upload_image']['tmp_name'])  && !empty($_FILES['upload_image']['name'] )){
            
                $target_dir = "../upload/";
                $target_file = $target_dir . basename($_FILES['upload_image']['name']);
                $upload_ok = 1;
                $image_file_type = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

            

                $check = getimagesize($_FILES['upload_image']['tmp_name']);
         
                if($check !== false)
                {
                    echo "File is an image - " . $check["mime"] . ".<br>";
                    $upload_ok = 1;
                }else 
                {
                        echo "File is not an image.<br>";
                        $upload_ok = 0;
                }


                    // Check if file already exists
                if(file_exists($target_file))
                {
                    unlink($target_file);
                    echo "Sorry, file already exists.<br>";
                    $upload_ok = 0;
                }
                    if(!file_exists($target_file))
                {




                // Check file size
                if($_FILES['upload_image']['size']> 500000)
                {
                    echo "Sorry, file is too large.<br>";
                    $upload_ok = 0;
                }

                // Allow certain file formats
                if($image_file_type != "jpg" && $image_file_type != "png" && $image_file_type != "jpeg") 
                {
                  echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
                  $upload_ok = 0;
                }
                if($upload_ok == 0)
                {
                    echo "Sorry, your file was not uploaded.<br>";
                }
                else
                {
                        if(move_uploaded_file($_FILES['upload_image']['tmp_name'], $target_file))
                        {

                            if(isset($_POST['new_content'])){
                                     $update_img->execute();
                                     header("Location: menu.php?menu=".$_POST['type_news']."#target".$_POST['id_news']);
                             }else if(isset($_POST['insert_news'])  ){
                                     $sql->execute();
                                 header("Location: ?succes");
                             }  
                               
                        } 
                        else 
                            {
                            echo "Sorry, there was an error uploading your file.<br>";
                            }
                }
                    }
        }else{
            $update_content->execute();
            header("Location: menu.php?menu=".$_POST['type_news']."#target".$_POST['id_news']);
        }
    }//End insertNewsData() method
    
    public function deleteNews($id_category):void
    {
        
        $deleted_news = $_POST['id_news'];
        $sql_unlink = $this->conn->prepare("SELECT * FROM sport_news.news WHERE `news`.`id_news` = :id_news ;");
        $sql_unlink->bindParam(':id_news', $deleted_news);
        $sql_unlink->execute();
       
        try{
        while($row = $sql_unlink->fetch()){
            
            $sql = $this->conn->prepare("DELETE FROM sport_news.news WHERE `news`.`id_news` = :id_news ;");
            $sql->bindParam(':id_news', $deleted_news);
           unlink($row['name_upload']);
            
           if(isset($_GET['delete']) && $_GET['delete'] == $_POST['id_news']){
                $sql->execute();
               
               
        }
            if(isset($_POST['delete_news'])){
                $sql->execute();
                header("Location: admin.inc.php?deleted");
        }
        }
        }catch(Exception $e){
            echo "ERROR path missing". $e->getMessage();
        }
        
        
        
        
        
        
      }
       public function updateNews()
    {
       //width out image    
        $sql = $this->conn->prepare("UPDATE sport_news.news SET id_category = :id_category, title = :title, content = :content
        WHERE news.id_news = :id_news;");
         try{   
            $sql->bindParam(':id_category', $_POST['type_news']);
            $sql->bindParam(':id_news', $_POST['id_news']);
            $sql->bindParam(':title', $_POST['title']);
            $sql->bindParam(':content', $_POST['content']);
            $sql->execute();
             echo "Updated succesfuly";
              header("Location: menu.php?menu=".$_POST['type_news']);
         }
           catch(PDOException $e)
           {
               echo "Error".$e->getMessage();
           }
            
    }

}
$a = new Admin();


if(isset($_GET['delete']) && $_GET['delete'] == $_POST['id_news']){//from menu pages
            $a->deleteNews($_POST['update_id_category']);
            header("Location: menu.php?menu=".$_POST['update_id_category']);
}else{
 if(isset($_POST['insert_news']) || isset($_POST['new_content'])){

        $a->insertNewsData();    
    }

if(isset($_GET['update']) && $_GET['update'] == $_POST['id_news'] ){

?>

<form class="input_news" action=""  method="post" enctype="multipart/form-data">
    <?php $a->category()?>
    <input type="hidden" name="id_news" value="<?php echo $_POST['id_news'];?>">
    <textarea type="text" name="title"   ><?php  echo $_POST['update_title']; ?></textarea>
    <textarea type="text" name="content" ><?php echo $_POST['update_content'];?></textarea>
    
    <img style="max-width:150px; height: 100%;" src="<?php echo $_POST['update_image'];?>" >
    
   <label style="float:right;" >
    <input type="file"    name="upload_image" class="file" >
    <input type="submit"  name="new_content"  value="Update" class="hidden_edit">
  </label>

</form>
<?php
          
}else{

        
?>



<form class="input_news" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data" method="post" >
    <?php
      if(isset($_GET['succes'])){
        echo "<h3 style='color:green; text-align:center; margin:20px;'>Your post is successfully published</h3>";
    }
      $a->category()?>
    <textarea type="text" name="title"   value="title"><?php  if(isset($_POST['title'])){echo $_POST['title'];} ?></textarea>
    <textarea type="text" name="content" value="content"><?php if(isset($_POST['content'])){ echo $_POST['content'];}?></textarea>
    <?php if(isset($_POST['update_image'])){ 

echo "<img style='max-width:150px; height: 100%;' src=".$_POST['update_image']." >";

}?>
    <input  type="file" name="upload_image"  class="file" >
    <input class="hidden_edit" style="float:right;" type="submit"  name="insert_news"  value="Upload content" >

</form>
<!--
<form class="input_news" action="" method="post">
    <h3>Enter the number of posts you want to check</h3>
    <input type="number" name="number">
    <input class="hidden_edit" type="submit" name="news_nr">
</form>
-->


<?php
   

     }
}
   if(isset($_POST['delete_news'])){//formo admin page
        $a->deleteNews(''); 
       
    }
       
            
                if(isset($_GET['hidden']) || isset($_GET['deleted']) || isset($_GET['delete']) && $_GET['delete'] == $_POST['id_news'] ){
                    ?>
                    <style> 
                        .container{
                            display: none;
                        }
                    </style>

                    <?php   
                }else{
                    if (isset($_POST['news_nr']) && $_POST['number'] >= 1 ){
                        echo $news->outputNews($_POST['number']);
                    }else{
                        echo $news->outputNews('1');
                    }
                    
                }


?>




    
    