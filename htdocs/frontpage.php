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
		$sql = "Select nomeAtivo, max(valor),min(valor), avg(valor)";
        $sql .= " FROM cotacao";
        $sql .= " inner join ativo on idAtivo = Ativo_idAtivo";
        $sql .= " where dataHora > now() - interval 7 day";
        $sql .= " group by Ativo_idAtivo";
        
        

		$stmt = $mysqli->prepare($sql);
		$stmt->execute();

        $stmt->bind_result($ativo, $max, $min, $media);

        printf("<tr><td><a href='login.php'> Realizar login</a></td></tr>");
        printf("</br>");
        printf("</br>");
		printf("<table>");
		printf("<tr><td><b>Ativo</b></td><td><b>Maximo(ultimos 7 dias)</b></td><td><b>Minimo(ultimos 7 dias)</b></td><td><b>Media(ultimos 7 dias)</b></td></tr>");

		while ($stmt->fetch()){
			printf("<tr>");
            printf("<td> $ativo </td>" );
            printf("<td> $max </td>" );
            printf("<td> $min </td>");
            printf("<td> $media </td>");
			printf("</tr>");
		}

        printf("</table>");
        printf("</br>");
        printf("<tr><td><a href='cotacao.php'> Ir para cotações</a></td></tr>");
		$mysqli->close();
	?>
</body>
</html>
