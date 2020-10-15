<?php

include_once "../scripts/Conexao-class.php";
include_once "../scripts/ConversorData-class.php";
$conversor = new ConversorData;
$conexao = new Conexao();
$link    = $conexao->getLink();

if (!isset($_POST['idEvento']) || $_POST['idEvento'] < 1) {
	echo 0;
	exit;
}

$idEvento = $_POST['idEvento'];

$sql    	 = "SELECT * FROM EVENTOS WHERE EVENTO_ID=".$idEvento." LIMIT 1";
$result 	 = mysqli_query($link, $sql);
$dadosEvento = $result->fetch_assoc();

$cliente    = $dadosEvento['EVENTO_CLIENTE_ID'];
$assunto    = $dadosEvento['EVENTO_ASSUNTO'];
$descricao  = $dadosEvento['EVENTO_DESCRICAO'];
$consultor  = $dadosEvento['EVENTO_CONSULTOR_ID'];
$ocorrencia = $dadosEvento['EVENTO_OCORRENCIA'];
$data       = $dadosEvento['EVENTO_DATA'];
$data       = $conversor->sql2brasil($data);

echo 
$cliente."|".$assunto."|".$descricao."|".$consultor."|".$ocorrencia."|".$data;
?>