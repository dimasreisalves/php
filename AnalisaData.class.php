<?php
	/*************************************************
	O objetivo desta classe eh obter a quantidade de
	chamados abertos e encerrados por mes, processando
	as informacoes provenientes da base de dados

	Autor: Alexandre Farias
	Data: 2017-01-21
	**************************************************/
	class AnalisaData
	{
		private $abertas = array(
			'Janeiro' => 0,
			'Fevereiro' => 0,
			'Março' => 0,
			'Abril' => 0,
			'Maio' => 0,
			'Junho' => 0,
			'Julho' => 0,
			'Agosto' => 0,
			'Setembro' => 0,
			'Outubro' => 0,
			'Novembro' => 0,
			'Dezembro' => 0
			);
		private $encerradas = array(
			'Janeiro' => 0,
			'Fevereiro' => 0,
			'Março' => 0,
			'Abril' => 0,
			'Maio' => 0,
			'Junho' => 0,
			'Julho' => 0,
			'Agosto' => 0,
			'Setembro' => 0,
			'Outubro' => 0,
			'Novembro' => 0,
			'Dezembro' => 0
			);

		/*Verifica se a data jah passou, considera a data no padrão brasileiro comparando primeiramente ano depois mes e depois dia*/
		function ehAtrasado($data)
		{
			$dataAtual = date("d/m/Y");
			$dataAtual = explode("/", $dataAtual);
			$dataEvento = explode("/", $data);

			if ($dataAtual[2] >= $dataEvento[2])
				if ($dataAtual[1] >= $dataEvento[1])
					if($dataAtual[1] > $dataEvento[1])
						return true;
					else if ($dataAtual[0] > $dataEvento[0])
						return true;
			return false;
		}

			function ehHoje($data)
			{
				$dataAtual = date("d/m/Y");
				$dataAtual = explode("/", $dataAtual);
				$dataEvento = explode("/", $data);
				$ehHoje = true;
				if ($dataAtual[2] != $dataEvento[2]) $ehHoje = false;
				if ($dataAtual[1] != $dataEvento[1]) $ehHoje = false;
				if ($dataAtual[0] != $dataEvento[0]) $ehHoje = false;
				return $ehHoje;
			}

			/*Retorna o arranjo abertas*/
			function getAbertas()
			{
				$abertas = "";
				foreach ($this->abertas as $key => $value) {
					$abertas .= $value.",";
				}
				$abertas = substr($abertas, 0, strlen($abertas) - 1);
				echo $abertas;
			}
			/*Retorna o arranjo encerradas*/
			function getEncerradas()
			{
				$encerradas = "";
				foreach ($this->encerradas as $key => $value) {
					$encerradas .= $value.",";
				}
				$encerradas = substr($encerradas, 0, strlen($encerradas) - 1);
				echo $encerradas;
			}
			/*Imprime os arranjos*/
			function imprimeArranjos()
			{
				echo "<br> <br> Abertas <br><br>";
				foreach($this->abertas as $i => $value)
				{
					echo $i.": ".$value."<br>";
				}
				echo "<br>-----------------------------<br><br>";
				echo "Encerradas <br><br>";
				foreach($this->encerradas as $i => $value)
				{
					echo $i.": ".$value."<br>";
				}
			}

			function getEmAndamento()
			{
				$emAndamento = "";
				foreach ($this->abertas as $i => $value)
				{
					$emAndamento .= ($value - $this->encerradas[$i]).",";
				}
				$emAndamento = substr($emAndamento, 0, strlen($emAndamento)-1);
				echo $emAndamento;
			}

			/*Retorna o nome do mes correspondente*/
			function obtemNomeMes($mes)
			{
				switch ($mes) 
				{
					case '01':
					return 'Janeiro';
					break;
					case '02':
					return 'Fevereiro';
					break;
					case '03':
					return 'Março';
					break;
					case '04':
					return 'Abril';
					break;
					case '05':
					return 'Maio';
					break;
					case '06':
					return 'Junho';
					break;
					case '07':
					return 'Julho';
					break;
					case '08':
					return 'Agosto';
					break;
					case '09':
					return 'Setembro';
					break;
					case '10':
					return 'Outubro';
					break;
					case '11':
					return 'Novembro';
					break;
					case '12':
					return 'Dezembro';
					break;
					default:
					return '0';
					break;
				}
			}

			/*Recebe uma data no padrao SQL e retorna o mes específico*/
			function montaArranjoAbertas ($data)
			{
				$date = explode("-", $data);
				$mes = $date[1];
				$mes = $this->obtemNomeMes($mes);

				if ($mes == 'Janeiro')
					$this->abertas['Janeiro']++;
				if ($mes == 'Fevereiro')
					$this->abertas['Fevereiro']++;
				if ($mes == 'Março')
					$this->abertas['Março']++;
				if ($mes == 'Abril')
					$this->abertas['Abril']++;
				if ($mes == 'Maio')
					$this->abertas['Maio']++;
				if ($mes == 'Junho')
					$this->abertas['Junho']++;
				if ($mes == 'Julho')
					$this->abertas['Julho']++;
				if ($mes == 'Agosto')
					$this->abertas['Agosto']++;
				if ($mes == 'Setembro')
					$this->abertas['Setembro']++;
				if ($mes == 'Outubro')
					$this->abertas['Outubro']++;
				if ($mes == 'Novembro')
					$this->abertas['Novembro']++;
				if ($mes == 'Dezembro')
					$this->abertas['Dezembro']++;
			}

			function montaArranjoEncerradas ($data)
			{
				$date = explode("-", $data);
				$mes = $date[1];
				$mes = $this->obtemNomeMes($mes);

				if ($mes == 'Janeiro')
					$this->encerradas['Janeiro']++;
				if ($mes == 'Fevereiro')
					$this->encerradas['Fevereiro']++;
				if ($mes == 'Março')
					$this->encerradas['Março']++;
				if ($mes == 'Abril')
					$this->encerradas['Abril']++;
				if ($mes == 'Maio')
					$this->encerradas['Maio']++;
				if ($mes == 'Junho')
					$this->encerradas['Junho']++;
				if ($mes == 'Julho')
					$this->encerradas['Julho']++;
				if ($mes == 'Agosto')
					$this->encerradas['Agosto']++;
				if ($mes == 'Setembro')
					$this->encerradas['Setembro']++;
				if ($mes == 'Outubro')
					$this->encerradas['Outubro']++;
				if ($mes == 'Novembro')
					$this->encerradas['Novembro']++;
				if ($mes == 'Dezembro')
					$this->encerradas['Dezembro']++;
			}
		}

		?>