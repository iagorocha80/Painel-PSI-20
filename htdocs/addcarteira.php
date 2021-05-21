<html>
<head>
	<title></title>
	<meta content="">
	<style></style>
	</head>
<body>
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
        printf("<tr><td><a href='carteira.php'> Ir para carteira</a></td></tr>");
		$user = $_SESSION["id"];
        printf("<b><p>Selecione a Carteira na qual desejas adiconar ativos</p></b>");

        $sql = "Select nomeCarteira, IdCarteira";
        $sql .= " FROM carteiradeativos";
        $sql .= " where Utilizadores_UserId = ?";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i",$user);
		$stmt->execute();
        $stmt->bind_result($nomeCarteira, $idCarteira);

        printf("</br>");
        printf("</br>");
		printf("<table>");
		printf("<p><td><b>Carteiras</b></p>");

        printf("<form action='' method='post'>");
        printf("<select name='Carteira'>");
		while ($stmt->fetch()){
            printf("<p>");
            printf("<option value = $idCarteira> $nomeCarteira </option>" );
            printf("</p>");
		}
        printf("<p>");
        printf("</select>");
        printf("<input type='submit' value='Enviar!'' />" );
        printf("</p>");
		printf("</form>");
        printf("</table>");

        if (isset($_POST['Carteira'])) {
            $idCarteira = $_POST['Carteira'];
        }
		
	?>

    <?php
		$sql = "Select nomeAtivo, idAtivo";
        $sql .= " FROM ativo";
        
        

		$stmt = $mysqli->prepare($sql);
		$stmt->execute();

        $stmt->bind_result($ativo, $idAtivo);
        printf("</br>");
        printf("</br>");
		printf("<table>");
		printf("<p><td><b>Interesses</b></p>");

		printf("<form action='' method='post'>");
		while ($stmt->fetch()){
            printf("<p>");
            printf("<input type='checkbox' name='Ativo[]' value='$idAtivo'> $ativo" );
            printf("</p>");
		}
        printf("<p>");
        printf("<input type='submit' value='Enviar!'' />" );
        printf("</p>");
		printf("</form>");

        printf("</table>");


        if (isset($_POST['Ativo']) && is_array($_POST['Ativo'])) {
            foreach($_POST['Ativo'] as $idAtivo){
                $sql= "INSERT INTO ativo_has_carteiradeativos (`Ativo_idAtivo`, `CarteiraDeAtivos_IdCarteira`) VALUES (?,?);";
                $stmt = $mysqli->prepare($sql);
                $stmt->bind_param("si",$idAtivo, $idCarteira);
		        $stmt->execute();
            } 
        }
        
		$stmt->close();
		$mysqli->close();
	?>

	
</body>
</html>

