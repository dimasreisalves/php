<!-- exportacao agenda -->
<?php
if (!isset($_POST['dataInicio'])) {
	echo "<script>history.back();</script>";
	exit;
}

include_once "../scripts/Conexao-class.php";
include_once "../scripts/ConversorData-class.php";
$conexao   = new Conexao();
$link      = $conexao->getLink();
$conversor = new ConversorData;

// obtencao das variaveis
$dataInicio = $_POST['dataInicio'];
$dataFim    = $_POST['dataFim'];

$sql = "SELECT DISTINCT rateio_consultor, rateio_agenda, rateio_chamado, rateio_percentual, 
	    rateio_hrtotal, rateio_hrref, agen_dtagenda, agen_cliente_id, OCOR_DTPRAZO,
		OCOR_DESC_RESUMIDA, OCOR_ID_MODULOS, MOD_DESCRICAO, OCOR_CONTATO, OCOR_CONSULTOR, USER_NOME		
		FROM
		RATEIO
		INNER JOIN AGENDA ON RATEIO_AGENDA = AGEN_ID
		INNER JOIN ocorrencias ON rateio_chamado = OCOR_ID
		INNER JOIN modulos ON MOD_ID = OCOR_ID_MODULOS
		INNER JOIN usuario ON OCOR_CONSULTOR = USER_ID		
		WHERE agen_dtagenda between 
		'".$conversor->brasil2Sql($dataInicio)."' 
		And 
		'".$conversor->brasil2Sql($dataFim)."'
		order by agen_dtagenda";

// realiza a query
$result = mysqli_query($link, $sql);

// struct
$html  = '';
$html .= "<table>		 	
		 	<tr style=\"font-weight: bold; text-align: center\">
		 		<td>Data Agenda</td>
		 		<td>Consultor</td>
		 		<td>Cliente</td>
		 		<td>ID Agenda</td>
		 		<td>Chamado</td>
		 		<td>Módulo</td>
		 		<td>Percentual</td>
		 		<td>Hora relativa</td>
		 		<td>Hora Total (Agenda)</td>
		 		<td>Status (Chamado)</td>
		 		<td>Data encerramento</td>
		 		<td>Qtde Dias Apontamentos</td>
		 		<td>Qtde Trocas de prazo</td>
		 		<td>OCOR_DESC_RESUMIDA</td>
		 		<td>OCOR_ID_MODULOS</td>
		 		<td>MOD_DESCRICAO</td>
		 		<td>OCOR_CONTATO</td>
		 		<td>Responsabilidade</td>
		 		<td>Prazo</td>
		 	</tr>";

		 while ($dados = $result->fetch_assoc()){
		 	
		 	// consultor
		 	$sql = "SELECT USER_NOME FROM USUARIO WHERE USER_ID=".$dados['rateio_consultor'];
		 	$resultAux     = mysqli_query($link, $sql);
		 	$nomeConsultor = $resultAux->fetch_assoc();
		 	$nomeConsultor = $nomeConsultor['USER_NOME'];

		 	// consultor
		 	$sql = "SELECT CLI_NREDUZ FROM CLIENTES WHERE cli_id=".$dados['agen_cliente_id'];
		 	$resultAux     = mysqli_query($link, $sql);
		 	$nomeCliente = $resultAux->fetch_assoc();
		 	$nomeCliente = $nomeCliente['CLI_NREDUZ'];

		 	//modulo do chamado
		 	$sql = "SELECT DISTINCT MOD_DESC_REDUZ, DATE(OCOR_DTENCERRAMENTO)
		 	        FROM MODULOS 
		 	        INNER JOIN OCORRENCIAS
		 	        ON OCOR_ID_MODULOS = MOD_ID
		 	        WHERE OCOR_ID = ".$dados['rateio_chamado']."";

			$resultAux     = mysqli_query($link, $sql);
		 	$nomeModulo = $resultAux->fetch_assoc();
		 	$dataEncerramento = '-';
		 	$status = 'ABERTO';
		 	
		 	if($nomeModulo['DATE(OCOR_DTENCERRAMENTO)'] != '0000-00-00') {
		 		$dataEncerramento = $conversor->sql2Brasil($nomeModulo['DATE(OCOR_DTENCERRAMENTO)']);
		 		$status = 'FECHADO';
		 	}
		 	$nomeModulo = $nomeModulo['MOD_DESC_REDUZ'];

		 	/* algoritmo de contagem de dias de apontamento de um determinado chamado 
				identificar chamado, buscar todos os assentamentos daquele chamado
				ter uma data default = 0;
				toda vez que a data do assentamento for diferente da default soma 1 na quantidade de dias e atualiza a data default
		 	*/

			$diaDefault = '0';
			$qtdeDias = 0;
			$qtdeTrocaPrazo = 0;

			$sql = "SELECT DATE(ASSE_DTINCLUSAO), ASSE_DESCRICAO
			        FROM assentamento
			        WHERE asse_id_ocorr=".$dados['rateio_chamado'];

			$resultAux = mysqli_query($link, $sql);

			while ($rowAux = $resultAux->fetch_assoc()) {
				if ($rowAux['DATE(ASSE_DTINCLUSAO)'] != $diaDefault) {
					$qtdeDias++;
					$diaDefault = $rowAux['DATE(ASSE_DTINCLUSAO)'];
				}
				if (strpos( $rowAux['ASSE_DESCRICAO'], "Prazo atualizado para:"))
					$qtdeTrocaPrazo++;
			}


		 	$html .= "<tr style=\"text-align: center\">
		 		<td>".$conversor->sql2Brasil($dados['agen_dtagenda'])."</td>
		 		<td>".$nomeConsultor."</td>
		 		<td>".$nomeCliente."</td>
		 		<td>".$dados['rateio_agenda']."</td>
		 		<td>".$dados['rateio_chamado']."</td>
		 		<td>".$nomeModulo."</td>
		 		<td>".($dados['rateio_percentual']*100)."%</td>
		 		<td>".substr($dados['rateio_hrref'], 0, 5)."</td>
		 		<td>".$dados['rateio_hrtotal']."</td>
		 		<td>".$status."</td>
		 		<td>".$dataEncerramento."</td>
		 		<td>".$qtdeDias."</td>
		 		<td>".$qtdeTrocaPrazo."</td>
		 		<td>".$dados['OCOR_DESC_RESUMIDA']."</td>
		 		<td>".$dados['OCOR_ID_MODULOS']."</td>
		 		<td>".$dados['MOD_DESCRICAO']."</td>
		 		<td>".$dados['OCOR_CONTATO']."</td>
		 		<td>".$dados['USER_NOME']."</td>
		 		<td>".$dados['OCOR_DTPRAZO']."</td>
		 	</tr> ";
		 	}
$html .= "</table>";

// Configurações header para forçar o download
header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/vnd.ms-excel;");
header ("Content-Disposition: attachment; filename=planilha-rateios.xls");
header ("Content-Description: PHP Generated Data");
// Envia o conteúdo do arquivo

echo $html;

exit;
?>