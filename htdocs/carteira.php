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

        printf("<tr><td><a href='userpage.php'> Ir para Newsletter</a></td></tr>");
        
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
        

        printf("</br>");
        printf("</br>");
	    printf("<table>");
		printf("<tr><td><b>Ativo</b></td><td><b>Media Mensal</b></td></tr>");

        $sql = "Select nomeAtivo, avg(valor)";
        $sql .= " from cotacao";
	    $sql .= " inner join ativo on idAtivo = cotacao.Ativo_idAtivo";
		$sql .= " inner join ativo_has_carteiradeativos on cotacao.Ativo_idAtivo = ativo_has_carteiradeativos.Ativo_idAtivo";
        $sql .= " where ativo_has_carteiradeativos.CarteiraDeAtivos_IdCarteira = ? and dataHora > now() - interval 30 day";
		$sql .= " group by cotacao.Ativo_idAtivo ;";

        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i",$idCarteira);
		$stmt->execute();
        $stmt->bind_result($nomeAtivo, $mensal);
                
        while ($stmt->fetch()){
            printf("<tr>");
            printf("<td> $nomeAtivo </td>" );
            printf("<td> $mensal </td>" );
            printf("</tr>");
        }
                
        printf("</table>");
        printf("</br>");
		printf("</br>");
        
		$stmt->close();

        printf("<form action=''>");
        printf("<input type='submit' name='delete' value='Deletar carteira' />");
        printf("</form>");

        if(isset($_GET['delete'])){
            $sql = "DELETE FROM `carteiradeativos` WHERE (`IdCarteira` = ?);";
            $stmt = $mysqli->prepare($sql);
            //echo $idCarteira;
            $stmt->bind_param("i",$idCarteira);
            $stmt->execute();
            $stmt->close();
        }

        printf("<b><p>Criar uma nova carteira</p></b>");
        printf("<table>");
        printf("<form action=''>");
        printf("<input type='text' id='novaCarteira' name='novaCarteira'><br>");
        printf("<p>");
        printf("</select>");
        printf("<input type='submit' value='Confirmar!' />" );
        printf("</p>");
		printf("</form>");
        printf("</table>");
        $nc = null;
        if(isset($_REQUEST["novaCarteira"])){
            $nc = $_REQUEST["novaCarteira"];
            //printf($nc);
            $sql = "INSERT INTO `carteiradeativos` (`nomeCarteira`, `Utilizadores_UserId`) VALUES (?, ?);";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("si",$nc, $user);
            $stmt->execute();
            $stmt->close();
        }
        $nc = null;

        printf("<form action=''>");
        printf("<input type='submit' name='add' value='Adicionar ativos a carteira' />");
        printf("</form>");
        if(isset($_GET['add'])){
            header("location: addcarteira.php");
        }

        $mysqli->close();
        
        printf("<tr><td><a href='cotacao.php'> Ir para cotações</a></td></tr>");
        printf("<br>");
        printf("<tr><td><a href='frontpage.php'> Ir para página Inicial</a></td></tr>");
		
	?>

	
</body>
</html>

