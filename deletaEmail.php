<?php 

function sair() {
	echo "<script>";
		echo "history.back();";
	echo "</script>";
	exit;
}

if (!isset($_SESSION)) session_start();
$userNivel = $_SESSION['userNivel'];

if (!isset($_GET['email']) || $userNivel != 'ADMINISTRADOR') sair();

//realiza conexao
include_once "../scripts/Conexao-class.php";
$conexao = new Conexao();
$link    = $conexao->getLink();

//realiza a delecao
$sql = "UPDATE email_pendente SET email_bloqueado='1' WHERE email_id=".$_GET['email'];

if (!mysqli_query($link, $sql)) {
	echo mysqli_error($link);
	sair();
} else {
	echo "<script>";
		echo "alert('email bloqueado')";
	echo "</script>";
	sair();
}


?>