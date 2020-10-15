<?php
include_once "../scripts/Conexao-class.php";
$conect = new Conexao();
$link = $conect->getLink();

$idCliente = $_POST['cliente'];

$sql = "SELECT * FROM cadastro_projetos WHERE PROJ_CLIENTE=".$idCliente;
$result = mysqli_query($link, $sql);

$combos = "<option value=0>...</option>";

while ($row = $result->fetch_assoc())
{
	$combos .= "<option value='".$row['PROJ_ID']."'>".$row['PROJ_DESCRICAO']."</option> ";
}
echo $combos;
?>