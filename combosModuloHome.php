<?php
include_once "../scripts/Conexao-class.php";
$conect = new Conexao();
$link = $conect->getLink();

$idConsultor = $_POST['idConsultor'];

$sql = "SELECT DISTINCT MOD_ID, MOD_DESCRICAO, MOD_DESC_REDUZ
		FROM MODULOS
		INNER JOIN ACESSOS
		ON ACE_ID_MODULOS = MOD_ID
		WHERE
		ACE_USUARIO_ID=".$idConsultor."
		AND
		ACE_BLOQUEADO != 1 ORDER BY MOD_DESCRICAO";
$result = mysqli_query($link, $sql);
$combos = "";

while ($row = $result->fetch_assoc())
{
	$combos .= "<option value='".$row['MOD_ID']."'>".$row['MOD_DESCRICAO']."</option> ";
}
if ($combos == "")
{
	$combos = "<option>...</option>";
}
echo $combos;
?>