
<?php
require('../conn/conexiune.php');






$sql_admin = "SELECT title, image, content, id_news FROM  news  ORDER BY id_news DESC LIMIT 5";
$result = $conn->query($sql_admin);

    if ($result->num_rows > 0) {

          while($row = $result->fetch_assoc()) {
              
          ?>     
   <div class="list_news_container"> 
        <div class="list_news">
    
        <form method="POST" action="delete.php?id=<?php echo $row['id_news']?> "  style="text-align:right;" onclick="return confirm('Be careful! This post may contain user messages that will be deleted once the post is deleted!');">
                <input class="last_post" type="submit" name="delete" value="Delete post" >
        </form>
            
            <!-- DELETE NEWS END-->
    
                    <h3 class="title_admin"> <?php echo $row['title']; ?> </h3>
                    <?php echo '<div class="img"><img class="db_image" src="data:image;base64,'.base64_encode($row['image']).'" alt="Image" ></div>';?>
                    <p style="text-align:left;"><?php echo $row['content'];?></p>

    </div>
</div>

        <?php
          }/* END WHILE*/

    } else {
          echo "0 results";    
          $conn->close();
        }

?>
