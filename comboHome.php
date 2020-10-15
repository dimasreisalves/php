<?php
include_once "../scripts/Conexao-class.php";
$conect = new Conexao();
$link = $conect->getLink();

$idCliente = $_POST['idCliente'];
$consultor = $_POST['consultor'];
$modulo = $_POST['modulo'];

if (!isset($_SESSION)) session_start();

$sql = "SELECT DISTINCT OCOR_ID, OCOR_DESC_RESUMIDA FROM OCORRENCIAS 
		INNER JOIN ACESSOS
		ON ACE_ID_MODULOS = OCOR_ID_MODULOS
		INNER JOIN USUARIO
		ON USER_ID = ACE_USUARIO_ID
		INNER JOIN MODULOS
		ON MOD_ID = OCOR_ID_MODULOS
		WHERE OCOR_ID_CLIENTE=".$idCliente." 
		AND OCOR_DTENCERRAMENTO = 0
		AND USER_ID='".$consultor."'
		AND MOD_ID = '".$modulo."'
		AND ACE_BLOQUEADO !=1;
		";

$result = mysqli_query($link, $sql);

$combos = "<option value=0>...</option> ";

while ($row = $result->fetch_assoc())
{
	$combos .= "<option value='".$row['OCOR_ID']."'> (".$row['OCOR_ID'].") ".$row['OCOR_DESC_RESUMIDA']."</option> ";
}

echo $combos;
?>