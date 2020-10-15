<?php 
if (isset($_GET['id'])) {
	include_once "../scripts/Conexao-class.php";
	$conexao = new Conexao();
	$link = $conexao->getLink();

	$idArquivo = $_GET['id'];
	$enderecoArquivo = $_GET['nome'];

	$sql = "SELECT * FROM arquivos_chamados WHERE ac_id=".$idArquivo." LIMIT 1";
	$result = mysqli_query($link, $sql);
	$dados = $result->fetch_assoc();

	$nome = basename($dados['ac_nome']);
	$tipo = $dados['ac_tipo'];

	header("Cache-Control: public");
    header("Content-Description: File Transfer");
    header("Content-Disposition: attachment; filename=".$nome."");
    header("Content-Type: ".$tipo."");
    header("Content-Transfer-Encoding: binary");

    readfile($enderecoArquivo);
}

 ?>