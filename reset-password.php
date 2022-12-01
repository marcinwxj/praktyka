<?php

session_start();
 

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
 

require_once "config.php";
 

$new_password = $confirm_password = "";
$new_password_err = $confirm_password_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
 
    if(empty(trim($_POST["new_password"]))){
        $new_password_err = "Podaj swoje nowe hasło";     
    } elseif(strlen(trim($_POST["new_password"])) < 6){
        $new_password_err = "Hasło musi zawierać przynajmniej 6 znaków";
    } else{
        $new_password = trim($_POST["new_password"]);
    }
    
 
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Potwierdź swoje hasło.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($new_password_err) && ($new_password != $confirm_password)){
            $confirm_password_err = "Hasło Nie pasuje.";
        }
    }
        
   
    if(empty($new_password_err) && empty($confirm_password_err)){
      
        $sql = "UPDATE users SET password = ? WHERE id = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
        
            mysqli_stmt_bind_param($stmt, "si", $param_password, $param_id);
            
       
            $param_password = password_hash($new_password, PASSWORD_DEFAULT);
            $param_id = $_SESSION["id"];
            
          
            if(mysqli_stmt_execute($stmt)){
              
                session_destroy();
                header("location: login.php");
                exit();
            } else{
                echo " Coś poszło nie tak, spróbuj ponownie później";
            }

         
            mysqli_stmt_close($stmt);
        }
    }
    

    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Zresetuj hasło</title>
    <link rel="stylesheet" href="for.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Zresetuj hasło</h2>
        <p>Wypełnij ten formularz aby zresetować hasło.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"> 
            <div class="form-group">
                <label>Nowe hasło</label>
                <input type="password" name="new_password" class="form-control <?php echo (!empty($new_password_err)) ? 'jest błędne' : ''; ?>" value="<?php echo $new_password; ?>">
                <span class="invalid-feedback"><?php echo $new_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Potwierdź hasło</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'jest błędne' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Potwierdź" id="boom">
               
            </div>
        </form>
    </div>    
</body>
</html>