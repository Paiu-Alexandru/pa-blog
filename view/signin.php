<?php 
//require '../inc/create_acount.inc2.php';
?>
<!doctype html>
<html> 
    <header>
        <meta charset="utf-8">    
        <link rel="stylesheet" type="text/css" href=" ../style.css" ><link>
        <meta name="viewport" content="width=device-width, initial-scale=1">        
       
    </header>

  <body>
     <div class="wraper">
     
      
                <div class="create_acount" >

                    <h2  id="required">All fields are required!</h2> 
                    
                       <form method="POST" action="../inc/create-acount.inc.php" >

                           <label>First name: </label> 
                           <input class="input" id="name" type="text" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name'];?>">     
                           <label>Last name:  </label> 
                           <input class="input" type="text"           name="surname" >   
                           <label>Username:    </label> 
                           <input class="input" type="text"           name="user_name" >     
                           <label>Password:    </label> 
                           <input class="input" type="password"       name="password" >  
                           <label>Email:       </label> 
                           <input class="input" type="email"          name="e_mail" >       

                              <input class="create_btn" style=" margin-top: 20px;" type="submit" value="Create acount" name="submit">
                              <input class="back_btn" type="submit" value="Close" formaction="../index.php" >
                        </form>
                           

                </div>

      <?php
        if(isset($_GET['error'])){
            $text = "Don't lie to me! I see you have empty fields.";
            echo "<script> 
                        document.getElementById('required').innerHTML = 'Don t lie to me! I see you have empty fields.';
                        document.getElementById('required').style.color ='red';                
                 </script>";
        }
      if(isset($_GET['error']) && $_GET['error'] == 'exists'){
            echo "<script> 
                        document.getElementById('required').innerHTML ='This username already exists, try another one!';
                        document.getElementById('required').style.color ='red';                
                 </script>";
        }
      ?>
         </div>
 </body>
</html>