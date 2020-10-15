<?php

class ChecaAtraso {
	private $idUsuario;

	function __construct() {
		if (!isset($_SESSION)) session_start();
		$this->idUsuario = $_SESSION['userId'];
	}

	/*realiza as querys em mysqli*/
	function query ($query) {
		include_once "../scripts/Conexao-class.php";
		$conexao = new Conexao();
		$link = $conexao->getLink();
		$result = mysqli_query($link, $query);
		return $result;
	}

	/*Verifica se existe algum evento em data anterior ainda não finalizado retornando um valor booleano */
	function checaAtrasoEvento () {
		$sql = "SELECT DISTINCT EVENTO_ID, EVENTO_DATA, EVENTO_DESCRICAO, EVENTO_MODULO
				FROM EVENTOS
                INNER JOIN ACESSOS
                ON EVENTO_MODULO = ACE_ID_MODULOS
                INNER JOIN usuario
                ON USER_ID = EVENTO_CONSULTOR_ID
				WHERE EVENTO_CONSULTOR_ID=".$this->idUsuario."
				AND EVENTO_OCORRENCIA = 0
                AND ACE_BLOQUEADO != 1";

		$result = $this->query($sql);
		while ($row = $result->fetch_assoc()) {
			$data = $row['EVENTO_DATA'];
			if ($this->ehDataAtrasada($data)) return true;
		}
		return false;
	}

	/*Verifica se existe algum chamado em data anterior ainda não finalizado retornando um valor booleano */
	function checaAtrasoChamado () {
		$sql = "SELECT OCOR_ID, OCOR_DTPRAZO
				FROM OCORRENCIAS
				WHERE OCOR_CONSULTOR=".$this->idUsuario."
				AND OCOR_DTENCERRAMENTO = 0
				AND OCOR_DTPRAZO != 0";
		$result = $this->query($sql);
		while ($row = $result->fetch_assoc()) {
			$data = $row['OCOR_DTPRAZO'];
			if ($this->ehDataAtrasada($data)) return true;
		}
		return false;
	}

	/*Verifica se existe alguma OS anterior ainda não liberada retornando um
	valor booleano */
	function checaAtrasoOS () {
		$sql = "SELECT DISTINCT AGEN_ID, AGEN_LIBERADO, AGEN_DTAGENDA
				FROM AGENDA
				WHERE AGEN_CONSULTOR = ".$this->idUsuario."
				AND AGEN_LIBERADO = 0 AND
				((AGEN_CLIENTE_ID != 99 AND AGEN_TIPO not like 'PARTICULAR') or (agen_cliente_id!= 99));";
		$result = $this->query($sql);
		while ($row = $result->fetch_assoc()) {
			$data = $row['AGEN_DTAGENDA'];
			if ($this->ehDataAtrasada($data)) return true;
		}
		return false;
	}

	/* recebe uma data no padrao sql xxxx-xx-xx e confere se eh anterior
	retornando um valor booleano */
	function ehDataAtrasada ($data) {
		$dataArray = explode("-", $data);
		$dia = $dataArray[2];
		$mes = $dataArray[1];
		$ano = $dataArray[0];
		$diaAtual = date("d");
		$mesAtual = date("m");
		$anoAtual = date("Y");
		
		if ($ano < $anoAtual)   return true;
		if ($ano > $anoAtual)   return false;
		if ($ano = $anoAtual)
			if ($mes <= $mesAtual)
				if ($mes < $mesAtual)
					return true;
				else {
					if ($dia < $diaAtual) return true;
				}

		return false;
	}

	function diff_absoluta ($data1, $data2) {
		$data1_array = explode("-", $data1);
		$data2_array = explode("-", $data2);
		$dia1 = $data1_array[2] + $data1_array[1]*30 + $data1_array[0]*365;
		$dia2 = $data2_array[2] + $data2_array[1]*30 + $data2_array[0]*365;
		return $dia1-$dia2;
	}
}

?>
