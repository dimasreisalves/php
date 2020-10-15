<?php 
include_once "../scripts/Conexao-class.php";

$conexao = new Conexao();
$link    = $conexao->getLink();

$data            = $_POST['data']; 
$cliente         = $_POST['cliente'];
$consultorAntigo = $_POST['consultorAntigo'];
$consultorNovo   = $_POST['consultorNovo'];

// monta um array ordenado com os acessos do consultor novo
$sql = "SELECT ace_id_modulos 
FROM acessos 
where ace_usuario_id=".$consultorNovo." 
AND ace_bloqueado != 1 order by ace_id_modulos";

$result = mysqli_query($link, $sql);
$acessosConsultorNovo = array(mysqli_num_rows($result));

$i = 0;
while ($row = $result->fetch_assoc()) {
	$acessosConsultorNovo[$i] = $row['ace_id_modulos'];
	$i++;
}

// acessosConsultorNovo eh um arranjo ordenado dos modulos aos quais o consultor novo tem acesso

include_once "../scripts/ConversorData-class.php";
$conversor = new ConversorData;

function contemExtra ($arranjo, $query, $parametro, $link) {
	$result = mysqli_query($link, $query);

	while ($row = $result->fetch_assoc()) {
		//inicializa como se nao tivesse encontrado
		$encontrou = 0;
		
		foreach ($arranjo as $modulo) {
			// se encontrou da um flag
			if ($row[$parametro] == $modulo) 
				$encontrou = 1;	
		}

		// terminou o laco e nao encontrou o modulo nos acessos do consultor novo retorna que tem acessos diferente
		if ($encontrou == 0) {
			if ($parametro == 'evento_modulo')   return 1;
			if ($parametro == 'ocor_id_modulos') return 2;
		}
	}

	return 0;
}

$resultado = 0;

// buscar os eventos do consultor antigo
$sql = "SELECT evento_modulo FROM eventos 
where evento_consultor_id=".$consultorAntigo." 
AND evento_data='".$conversor->brasil2Sql($data)."'";

$resultado += contemExtra ($acessosConsultorNovo, $sql, 'evento_modulo', $link);

// buscar os chamados do consultor antigo
$sql = "SELECT ocor_id_modulos FROM ocorrencias
WHERE ocor_consultor=".$consultorAntigo." 
AND ocor_dtprazo ='".$conversor->brasil2Sql($data)."'";

$resultado += contemExtra ($acessosConsultorNovo, $sql, 'ocor_id_modulos', $link);

echo $resultado;

$conexao->fechar();
exit;
?>