<?php 
include_once "../scripts/Conexao-class.php";
$conexao = new Conexao();
$link    = $conexao->getLink();

//recebe uma data no formato xx/xx
$data = $_POST['data'];
$cliente = $_POST['cliente'];

$sql = "SELECT feriado_data from feriados WHERE (feriado_cliente=".$cliente." AND feriado_data='".$data."') OR (feriado_cliente=0 AND feriado_data='".$data."')";
$result = mysqli_query($link, $sql);
if (mysqli_num_rows($result) > 0) {
 echo "1";
 exit;
}

echo "0";
?>