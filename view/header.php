<?php
session_start();
require ('../conn/conn.php');
require ('../inc/menu-comments.inc.php');
require('../inc/listnews.inc.php');
if(!$_SESSION){ header("location: ../index.php");}
 ?>


<!doctype html>
<html>  
    <header>
        <meta charset="utf-8">
        <link rel="stylesheet" href="../style.css" type="text/css">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    </header>



<body>

         
       <script>
function openNav() {
  document.getElementById("nav").style.width = "100%";
  document.getElementById("close_btn").style.marginLeft = "100%";
}

function closeNav() {
  document.getElementById("nav").style.width = "0";
  document.getElementById("close_btn").style.marginLeft= "0";
}
</script>
    

         
 <!--REsponsitive menu-->
         <div id="nav" class="nav_resp" >
        
          <!--TAKE News CATEGORY VALUE from menu_comments.inc.php-->
               <?php $menu = new Menu(); $menu->menuData();?>
              
         </div> 
      
    <div id="close_btn"  >
     <span onclick="openNav()"><i class="fa fa-bars"></i></span>
    </div>
<!--END Responsitive menu-->
    
    
    
     <!--Sample menu-->
    
     <div class="nav_container" id="nav_links" >
         <div class="nav_links" >
        
          <!--TAKE News CATEGORY VALUE from menu_comments.inc.php-->
               <?php $menu = new Menu(); $menu->menuData();?>
              
         </div> 
    </div>
    
            <form class="logout_form"  method="POST" action="" >
                <?php  //Create a print news button for ADMIN  
                if($_SESSION['user_name'] === 'administrator'){
                       echo 
            ' <input class="print_news_btn"  type="submit" name="print" value="Print News" formaction="admin.inc.php" >';
                }?>
                <input class="log_out"  type="submit" value="Logout" name="log_out" onclick="return confirm('Are you sure! \nDo you want to leave us?')" formaction="../index.php" >
            </form> 
<?php
           
    
    
    
    if(isset($_POST['log_out']))
    {
        
       session_destroy();
        
       
    }
    
?>
