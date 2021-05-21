<?php 
	// liga à base dados 
	include("config.php");		        
	$mysqli = new mysqli($host, $user, $pw, $bd); 
	if ($mysqli->connect_errno){
	    die("Erro fatal: " . $mysqli->connect_error);
	}	
?>

<?php
    session_start();
    //confere se a sessão do utilizador está ativa
         if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === false){
            header("location: login.php");
            exit;
        }
?>

<?php
        $user = $_SESSION["id"];

        if (isset($_POST['Ativo']) && is_array($_POST['Ativo'])) {
            foreach($_POST['Ativo'] as $idAtivo){
                $sql= "INSERT INTO interesse (`Utilizadores_UserId`, `Ativo_idAtivo`) VALUES (?,?);";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("is",$user, $idAtivo);
		        $stmt->execute();
            } 
        }
        header("location: userpage.php");
        exit;
        $stmt->close();
		$mysqli->close();
	?>