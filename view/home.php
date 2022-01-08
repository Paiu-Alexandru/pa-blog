<?php
require ('header.php');




if(isset($_POST['log_out'])){
    session_destroy();
    header("location: ../index.php");            
}
                echo "<h1 class='welcome'>Welcome <span>". $_SESSION['name']. '  ' .$_SESSION['surname'] .  "!</span><br> Last news for you...</h1>";
                echo $news->outputNews('7'); //Last News output
          
            ?>
    
