<?php
// Inicia a sessão
session_start();
 
// confere se o usuario já tem login feito
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: userpage.php");
    exit;
}
 
// Liga a base de dados
include("config.php");		        
	    $mysqli = new mysqli($host, $user, $pw, $bd); 
	    if ($mysqli->connect_errno){
	            die("Erro fatal: " . $mysqli->connect_error);
	    }
 
// Inicialização das variaveis de login
$username = $password = "";
$username_err = $password_err = "";
 
// Processamento do login
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Confere se o campo username está vazio
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Confere se o campo password está vazio
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Valida os dados
    if(empty($username_err) && empty($password_err)){
        
        $sql = "SELECT UserId, email, Pass FROM utilizadores WHERE email = ?";
        
        if($stmt = $mysqli->prepare($sql)){
            
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            
            $param_username = $username;
            
            
            if(mysqli_stmt_execute($stmt)){
                
                mysqli_stmt_store_result($stmt);
                
                
                if(mysqli_stmt_num_rows($stmt) == 1){                    
                    
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            
                            session_start();
                            
                            
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;                            
                            
                            
                            header("location: userpage.php");
                        } else{
                            
                            $password_err = "Password invalida.";
                        }
                    }
                } else{
                    
                    $username_err = "Nao existe nenhuma conta com este email";
                }
            } else{
                echo "Oops!";
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
    <title>Login</title>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Preencha com os seus dados de login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Nao tem registo? <a href="registo.php">Registe-se agora</a>.</p>
        </form>
    </div>    
</body>
</html>