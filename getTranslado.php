<?php 
include_once "../scripts/Conexao-class.php";
$conexao = new Conexao();
$link    = $conexao->getLink();

$idAgenda = $_POST['idAgenda'];

$sql = "SELECT DISTINCT
		CLI_TRANSLADO
		FROM AGENDA
		INNER JOIN CLIENTES
		ON AGEN_CLIENTE_ID = CLI_ID
		WHERE AGEN_ID = '".$idAgenda."' LIMIT 1";

$result = mysqli_query($link, $sql);

if (!$result) {
	echo mysqli_error($link);
	$conexao->fechar();
	exit();
}

$result = $result->fetch_assoc();
$result = $result['CLI_TRANSLADO'];

echo $result;

$conexao->fechar();
?>