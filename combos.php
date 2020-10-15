<?php
include_once "../scripts/Conexao-class.php";
$conect = new Conexao();
$link = $conect->getLink();

$idModulo = $_POST['modulo'];

$sql = "SELECT * FROM cadastro_problemas WHERE PROB_ID_MODULOS=".$idModulo;
$result = mysqli_query($link, $sql);
$combos = "";

while ($row = $result->fetch_assoc())
{
	$combos .= "<option value='".$row['PROB_ID']."'>".$row['PROB_DESCRICAO']."</option> ";
}
if ($combos == "")
{
	$combos = "<option>...</option>";
}
echo $combos;
?>