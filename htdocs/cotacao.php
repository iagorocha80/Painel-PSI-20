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
		printf("<td> <a href='frontpage.php'> Ir para a página inicial</a> </td>");
		$sql = "SELECT Ativo_idAtivo, valor, dataHora";
		$sql .= " FROM cotacao ORDER BY IdCotacao Desc limit 17;";

		$stmt = $mysqli->prepare($sql);
		$stmt->execute();

		$stmt->bind_result($id, $valor, $hora);
		printf("<table>");
		printf("<tr><td><b>Ativo</b></td><td><b>valor</b></td><td></td><td><b>Ultima atualização</b></td></tr>");

		while ($stmt->fetch()){
			printf("<tr>");
            printf("<td> $id </td>" );
			printf("<td> $valor </td>");
			printf("<td></td>");
			printf("<td> $hora </td>" );
			printf("</tr>");
		}

		printf("</table>");

		printf("<td> <a href='carteira.php'> Ir para a carteira</a> </td>");

		$mysqli->close();
	?>
</body>
</html>
