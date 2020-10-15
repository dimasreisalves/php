<?php 
if (isset($_GET['user'])) {
	$usersha = $_GET['user'];

	include_once "../scripts/Conexao-class.php";
	$conexao = new Conexao();
	$link = $conexao->getLink();

	$sql = "SELECT USER_LOGIN FROM USUARIO WHERE USER_SHA1='".$usersha."' LIMIT 1";
	$result = mysqli_query($link, $sql);

	if (mysqli_num_rows($result) > 0) {
		$dados = $result->fetch_assoc();
		$email = $dados['USER_LOGIN'];

		$sql = "UPDATE USUARIO 
		        SET USER_BLOQUEADO=0, USER_SHA1='0' 
		        WHERE USER_LOGIN='".$email."'";

		if (!mysqli_query($link, $sql)) {
			echo mysqli_error($link);
			$conexao->fechar();
			exit;
		}

		$conexao->fechar();

		echo "<script>";
			echo "alert('Confirmaçao efetuada, redirecionando para login.');";
			echo "window.location.replace('index.php');";
		echo "</script>";
	} else {
		echo "<script>";
			echo "alert('Nenhum usuário encontrado.');";
			echo "window.location.replace('index.php');";
		echo "</script>";
	}

	$conexao->fechar();
}
?>