<?php

 
	    // liga à base dados 
	    include("config.php");		        
	    $mysqli = new mysqli($host, $user, $pw, $bd); 
	    if ($mysqli->connect_errno){
	            die("Erro fatal: " . $mysqli->connect_error);
	    }	
	
 
// Define as variaveis de login
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";
 
// Processamento do registo
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Valida o username
    if(empty(trim($_POST["username"]))){
        $username_err = "Favor digitar e-mail";
    } else{
        
        $sql = "SELECT UserId FROM utilizadores WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            
            $param_username = trim($_POST["username"]);
            
            
            if(mysqli_stmt_execute($stmt)){
                
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "E-mail já cadastrado";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops!";
            }

            
            mysqli_stmt_close($stmt);
        }
    }
    
    // Valida password
    if(empty(trim($_POST["password"]))){
        $password_err = "Favor digitar uma senha";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "A deve deve ter ao menos 6 caracteres";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Valida confirmação da pass
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Favor confirmar a senha";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "As senhas são diferentes";
        }
    }
    
    // Confere se não há erros
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        
        // Guarda na base
        $sql = "INSERT INTO utilizadores (email, Pass) VALUES (?, ?)";
         
        if($stmt = $mysqli->prepare($sql)){
            
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Cria a hash da pass
            
            
            if(mysqli_stmt_execute($stmt)){
                // Redireciona para o login
                header("location: login.php");
            } else{
                echo "Aconteceu algum erro.";
            }

            
            mysqli_stmt_close($stmt);
        }
    }
    
    
    $mysqli->close();
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
</head>
<body>
    <div class="wrapper">
        <h2>Registe-se</h2>
        <p>Preeencha o formulário para tal</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>E-mail</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Senha</label>
                <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                <label>Confirmar Senha</label>
                <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                <span class="help-block"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Confirmar">
            </div>
            <p>Já tens registo? <a href="login.php"> Proceder para o Login</a>.</p>
        </form>
    </div>    
</body>
</html>