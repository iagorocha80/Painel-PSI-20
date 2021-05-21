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
		$user = $_SESSION["id"];

		
		
		$sql = "Select conteudo, link, IdNoticia";
        $sql .= " from noticias";
		$sql .= " inner join noticias_has_ativo on IdNoticia = Noticias_IdNoticia";
		$sql .= " inner join interesse on interesse.Ativo_idAtivo = noticias_has_ativo.Ativo_idAtivo";
		$sql .= " where interesse.Utilizadores_UserId = ? ;";

		$stmt = $mysqli->prepare($sql);
		$stmt->bind_param("i",$user);
		$stmt->execute();
		$stmt->bind_result($texto, $lig, $newsId);

		printf("<form action=''>");
        printf("<input type='submit' name='news' value='Visualizar noticias de Interesse' />");
        printf("</form>");

		if(isset($_GET['news'])){
			printf("</br>");
        	printf("</br>");
			printf("<table>");
			printf("<tr><td><b>Noticia</b></td><td><b>Fonte</b></td></tr>");
			while ($stmt->fetch()){
				printf("<tr>");
            	printf("<td> $texto </td>" );
            	printf("<td> $lig </td>" );
				printf("</tr>");
			}
			printf("</table>");
        	printf("</br>");
			printf("</br>");
		}
		
		$stmt->close();	
	?>

    <?php
		$sql = "Select nomeAtivo, idAtivo";
        $sql .= " FROM ativo";
        
        

		$stmt = $mysqli->prepare($sql);
		$stmt->execute();

        $stmt->bind_result($ativo, $idAtivo);
        printf("<tr><td><a href='frontpage.php'> Ir para página inicial</a></td></tr>");
        printf("</br>");
        printf("</br>");
		printf("<table>");
		printf("<p><td><b>Interesses</b></p>");

		printf("<form action='action.php' method='post'>");
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
        
		$stmt->close();
		$mysqli->close();

		printf("<tr><td><a href='cotacao.php'> Visualizar as cotações mais recentes</a></td></tr>");
		printf("</br>");
		printf("<tr><td><a href='carteira.php'> Ir para a carteira</a></td></tr>");
	?>

	
</body>
</html>

