<?php
require'inc/listnews.inc.php';
require ('conn/conn.php');
?>
<!--Wow Interesant ..............155555-->
<!doctype html>
<html>  
    <header>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </header>

        <body>

    
 
    <div class="container_login" >    
        <h1 id="hello">Login for more news </h1>
        
            <div class="login">
                    <form method="POST" action="inc/login.inc.php">
                            <span class="error"><?php// if(isset($_POST['password'])){ echo $user_name_error;} ?></span>
                            <input class="login_input"  name="user_name" placeholder="Username">

                            <span class="error"><?php// if(isset($_POST['password'])){ echo $pass_error;}?></span>
                            <input class="login_input" type="password" name="password" placeholder="Password">

                            <input class="login_btn" type="submit" value="Login" name="log_in"> 
                            <input class="create_acount_btn" type="submit" value="Create acount" name="log_in" formaction="view/signin.php?create">
                     </form>

            </div>
    </div> 
        
  
<?php 
    $news = new News(); 
    echo $news->outputNews("30");   
?>
  

</body>

</html>























