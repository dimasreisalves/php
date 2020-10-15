<?php
include_once "../scripts/Conexao-class.php";
$conect = new Conexao();
$link = $conect->getLink();

if (!isset($_SESSION)) session_start();

	//pega nivel do usuario
if (!isset($_SESSION)) session_start();
$nivelUser = $_SESSION['userNivel'];

//testes nova funcao
include_once "ChecaAtraso.class.php";
$checaAtraso = new ChecaAtraso();
if($checaAtraso->checaAtrasoChamado() || $checaAtraso->checaAtrasoOS()) $temChamadoAtrasado = true;
else $temChamadoAtrasado = false;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Solução Compacta</title>

	<!-- Bootstrap -->
	<link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- NProgress -->
	<link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
	<!-- iCheck -->
	<link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	<!-- bootstrap-wysiwyg -->
	<link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
	<!-- Switchery -->
	<link href="../vendors/switchery/dist/switchery.min.css" rel="stylesheet">
	<!-- bootstrap-daterangepicker -->
	<link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
	<!-- Datatables -->
	<link href="../vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="../vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
	<link href="../vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
	<link href="../vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
	<link href="../vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
	<!-- starrr -->
	<link href="../vendors/starrr/dist/starrr.css" rel="stylesheet">
	<!-- Select2 -->
	<link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">

	<!-- Custom Theme Style -->
	<link href="../build/css/custom.min.css" rel="stylesheet">
	<!-- LOADER -->
	<link href='loader.css' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Lato:900,400' rel='stylesheet' type='text/css'>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script>
		function loadOff()
		{
			$(document).ready(function (){
				$("#loader-wrapper").fadeOut('slow');
			});
		}
	</script>
	<!-- /LOADER -->

	<style type="text/css">
		/* Tooltip container */
		.tooltip {
			position: relative;
			display: inline-block;
			border-bottom: 1px dotted black; /* If you want dots under the hoverable text */
		}

		/* Tooltip text */
		.tooltip .tooltiptext {
			visibility: hidden;
			width: 120px;
			background-color: black;
			color: #fff;
			text-align: center;
			padding: 5px 0;
			border-radius: 6px;

			/* Position the tooltip text - see examples below! */
			position: absolute;
			z-index: 1;
		}

		/* Show the tooltip text when you mouse over the tooltip container */
		.tooltip:hover .tooltiptext {
			visibility: visible;
		}
	</style>

</head>

<body class="nav-md" onload="loadOff()">

	<div class="container body">
		<div class="main_container">
			<!-- MENU DE NAVEGAÇÃO LATERAL -->
			<?php
			include_once "MontaMenu.php";
			$menu = new MontaMenu();
			?>
			<!-- FIM MENU DE NAVEGAÇÃO LATERAL -->

			<!-- CONTEÚDO DA PÁGINA-->
			<div class="right_col" role="main">
				<div class="">
					<!-- TÍTULO -->
					<div class="page-title">
						<div class="title_left">
							<h3>Filtrar ocorrências</h3>
						</div>
					</div>
					<!-- ROW CADASTROS -->
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">

							<!-- TABELA DE CONSULTAS DE ocorrências-->
							<div class="x_panel">
								<div class="x_title">
									<label>FILTROS COMPLEMENTARES</label><br><br>
									<div class="form-group">
										<form>

											<div hidden>
												<!-- Componente consultor -->
												<label class="control-label col-md-1 col-sm-6 col-xs-12">Consultor:</label>
												<div class="col-md-3 col-sm-3 col-xs-12">
													<select tabindex="-1" class="form-control col-md-3 col-sm-6 col-xs-12" name="filtroConsultor" id="filtroConsultor">
														<option></option>";
														<?php
														include_once "../scripts/Conexao-class.php";
														$conect = new Conexao();
														$link = $conect->getLink();
														if (!isset($_SESSION)) session_start();

													//se eh administrador ou consultor interno
														$nivel = $_SESSION['userNivel'];
														if ($nivel == 'ADMINISTRADOR' || ($nivel == 'CONSULTOR' && $_SESSION['userTipo'] == 'INTERNO'))
															$sql = "SELECT * FROM CONSULTOR";

													//se eh consultor externo
														if ($nivel == 'CONSULTOR' && $_SESSION['userTipo'] == 'EXTERNO')
															$sql = "SELECT * FROM CONSULTOR WHERE CONS_ID=".$_SESSION['userId'];

													//se eh usuario nao puxa nada
														if ($nivel == 'USUÁRIO')
															$sql = "SELECT * FROM CONSULTOR WHERE CONS_ID < 0";


														$result = mysqli_query($link,$sql);
														$i = 1;
														while ($row = $result->fetch_assoc())
														{
															echo "<option value=".$row['CONS_ID']."> ".$row['CONS_NOME']." </option>";
															$i++;
														}
														?>
													</select><br>
												</div>

												<!-- Componente Cliente -->
												<label class="control-label col-md-1 col-sm-6 col-xs-12">Cliente:</label>
												<div class="col-md-3 col-sm-6 col-xs-12">
													<select tabindex="-1" class="form-control col-md-3 col-xs-3 col-sm-3" name="filtroCliente" id="filtroCliente">
														<option></option>";
														<?php
														include_once "../scripts/Conexao-class.php";
														$conect = new Conexao();
														$link = $conect->getLink();

													//se eh administrador ou consultor interno
														$nivel = $_SESSION['userNivel'];
														if ($nivel == 'ADMINISTRADOR' || ($nivel == 'CONSULTOR' && $_SESSION['userTipo'] == 'INTERNO'))
															$sql = "SELECT * FROM CLIENTES";

													//se eh consultor externo
														if ($nivel == 'CONSULTOR' && $_SESSION['userTipo'] == 'EXTERNO' || $nivel == 'USUÁRIO')
															$sql = "SELECT * FROM CLIENTES WHERE CLI_ID=".$_SESSION['userEmpresa'];

														$result = mysqli_query($link,$sql);
														$i = 1;
														while ($row = $result->fetch_assoc())
														{
															echo "<option value=".$row['CLI_ID']."> ".$row['CLI_NOME']." </option>"
															;
															$i++;
														}
														?>
													</select>
													<br><br>
													<!-- Componente Cliente -->
												</div>
											</div>


											<!-- Componente Módulo -->
											<label class="control-label col-md-1 col-sm-6 col-xs-12">Módulo:</label>
											<div class="col-md-3 col-sm-6 col-xs-12">
												<select tabindex="-1" class="form-control col-md-3 col-xs-3 col-sm-3" name="filtroModulo" id="filtroModulo">
													<option></option>";
													<?php
													include_once "../scripts/Conexao-class.php";
													$conect = new Conexao();
													$link = $conect->getLink();

													//se eh administrador
													$nivel = $_SESSION['userNivel'];
													if ($nivel == 'ADMINISTRADOR')
														$sql = "SELECT * FROM MODULOS ORDER BY
													MOD_DESCRICAO ASC";

													//se eh consultor externo ou interno
													if ($nivel == 'CONSULTOR' || $nivel == 'USUÁRIO')
														$sql = "
													SELECT DISTINCT
													MOD_ID, MOD_DESC_REDUZ, MOD_DESCRICAO FROM MODULOS
													INNER JOIN
													ACESSOS
													ON
													ACE_ID_MODULOS = MOD_ID
													INNER JOIN
													USUARIO
													ON
													USER_ID = ACE_USUARIO_ID
													WHERE
													USER_ID=".$_SESSION['userId']."
													AND
													ACE_BLOQUEADO != 1
													ORDER BY
													MOD_DESCRICAO ASC
													";

													$result = mysqli_query($link,$sql);
													$i = 1;
													while ($row = $result->fetch_assoc())
													{
														echo "<option value=".$row['MOD_ID']."> ".$row['MOD_DESCRICAO']." </option>"
														;
														$i++;
													}
													?>
												</select>
												<br><br>
												<!-- Componente Cliente -->
											</div>

											<!-- Componente Encerrado -->
											<label class="control-label col-md-1 col-sm-6 col-xs-12">Encerrado:</label>
											<div class="col-md-3 col-sm-6 col-xs-12">
												<select tabindex="-1" class="form-control col-md-8 col-xs-3 col-sm-3" name="filtroEncerrado" id="filtroEncerrado">
													<option></option>
													<option>SIM</option>
													<option>NÃO</option>
												</select>
												<br><br>
												<!-- Componente Encerrado -->
											</div>

											<!-- Botões-->
											<div class="col-md-3 col-sm-6 col-xs-12">
												<button class="btn btn-primary">Ok</button>
												<a href="filtrarocorrencias.php" class="btn btn-info">Limpar filtro</a>
											</div>
										</form>

									</div>
									<div class="x_content">
										<br>
										<!-- consulta ocorrencias cadastrados no banco-->
										<?php
										include_once "../scripts/Conexao-class.php";
										$conect = new Conexao();
										$db= $conect->getLink();

					//define a query
										if (!isset($_SESSION)) session_start();

										if ($_SESSION['userNivel'] == 'USUÁRIO')
										{
						//indicar o usuário também é necessário
											if(!isset($_GET['filtroEncerrado']) || $_GET['filtroEncerrado'] == "")
											{
												$sql = "
												SELECT DISTINCT OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_ID_USUARIO, OCOR_ID_CLIENTE, OCOR_DTENCERRAMENTO, OCOR_CONSULTOR, OCOR_DESCRICAO, OCOR_ID_PROB, OCOR_DTINCLUSAO, OCOR_ID_MODULOS, OCOR_CONSULTOR, OCOR_DTPRAZO, OCOR_CONTATO, OCOR_CLASSIFICACAO
												FROM OCORRENCIAS
												INNER JOIN
												USUARIOS_X_CLIENTES
												ON
												USERXCLI_CLIENTE = OCOR_ID_CLIENTE
												INNER JOIN
												USUARIO
												ON
												USER_ID = USERXCLI_ID_USUARIO
												INNER JOIN
												ACESSOS
												ON
												ACE_USUARIO_ID = USER_ID
												AND
												ACE_ID_MODULOS = OCOR_ID_MODULOS
												WHERE
												OCOR_ID_CLIENTE = ".$_SESSION['userEmpresa']."
												AND
												USERXCLI_ID_USUARIO = ".$_SESSION['userId']."
												AND
												OCOR_DTENCERRAMENTO = 0
												AND
												ACE_BLOQUEADO != 1
												";
											}
											else
											{
												$sql = "
												SELECT DISTINCT OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_ID_USUARIO, OCOR_ID_CLIENTE, OCOR_DTENCERRAMENTO, OCOR_CONSULTOR, OCOR_DESCRICAO, OCOR_ID_PROB, OCOR_DTINCLUSAO, OCOR_ID_MODULOS, OCOR_CONSULTOR, OCOR_DTPRAZO, OCOR_CONTATO, OCOR_CLASSIFICACAO
												FROM OCORRENCIAS
												INNER JOIN
												USUARIOS_X_CLIENTES
												ON
												USERXCLI_CLIENTE = OCOR_ID_CLIENTE
												INNER JOIN
												USUARIO
												ON
												USER_ID = USERXCLI_ID_USUARIO
												INNER JOIN
												ACESSOS
												ON
												ACE_USUARIO_ID = USER_ID
												AND
												ACE_ID_MODULOS = OCOR_ID_MODULOS
												WHERE
												OCOR_ID_CLIENTE = ".$_SESSION['userEmpresa']."
												AND
												USERXCLI_ID_USUARIO = ".$_SESSION['userId']."
												AND
												ACE_BLOQUEADO != 1
												";
											}

											if (isset($_GET['filtroEncerrado']) || ($_SESSION['filtroConsultor']!= 0 || $_SESSION['filtroCliente'] != 0) )
											{

												if (isset($_GET['filtroModulo'])) $modulo = $_GET['filtroModulo'];
												else $modulo = "";

												if (isset($_GET['filtroEncerrado'])) $encerrado = $_GET['filtroEncerrado'];
												else $encerrado = "";

												$consultor = $_SESSION['filtroConsultor'];
												$cliente   = $_SESSION['filtroCliente'];

												if (isset($_GET['filtroConsultor']) && $_GET['filtroConsultor'] != "")
													$consultor = $_GET['filtroConsultor'];
												if (isset($_GET['filtroCliente']) && $_GET['filtroCliente'] != "")
													$cliente = $_GET['filtroCliente'];

												if ($consultor != 0 && $consultor != "")
													$sql .= " AND OCOR_CONSULTOR=".$consultor;

												if ($cliente != 0 && $cliente != "")
													$sql .= " AND OCOR_ID_CLIENTE=".$cliente;

												if ($modulo != "")
													$sql .= " AND OCOR_ID_MODULOS=".$modulo;

												if ($encerrado == 'SIM')
													$sql .= " AND OCOR_DTENCERRAMENTO != 0";
												else if ($encerrado == 'NÃO')
													$sql .= " AND OCOR_DTENCERRAMENTO = 0";
											}
										}
					//se eh consultor busca apenas as ocorrencias que possuem modulos quais o mesmo tem acesso
										if ($_SESSION['userNivel'] == 'CONSULTOR')
										{
											if(!isset($_GET['filtroEncerrado']) || $_GET['filtroEncerrado'] == "")
											{
												$sql = "
												SELECT DISTINCT OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_ID_USUARIO, OCOR_ID_CLIENTE, OCOR_DTENCERRAMENTO, OCOR_CONSULTOR, OCOR_DESCRICAO, OCOR_ID_PROB, OCOR_DTINCLUSAO, OCOR_ID_MODULOS, OCOR_CONSULTOR, OCOR_DTPRAZO, OCOR_CONTATO, OCOR_CLASSIFICACAO
												FROM OCORRENCIAS
												INNER JOIN ACESSOS
												ON OCOR_ID_MODULOS = ACE_ID_MODULOS
												WHERE
												OCOR_DTENCERRAMENTO = 0
												AND ACE_USUARIO_ID=".$_SESSION['userId']."
												AND ACE_BLOQUEADO != 1";

											}else{
												$sql = "
												SELECT DISTINCT OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_ID_USUARIO, OCOR_ID_CLIENTE, OCOR_DTENCERRAMENTO, OCOR_CONSULTOR, OCOR_DESCRICAO, OCOR_ID_PROB, OCOR_DTINCLUSAO, OCOR_ID_MODULOS, OCOR_CONSULTOR, OCOR_DTPRAZO, OCOR_CONTATO, OCOR_CLASSIFICACAO
												FROM OCORRENCIAS
												INNER JOIN ACESSOS
												ON OCOR_ID_MODULOS = ACE_ID_MODULOS
												WHERE
												ACE_USUARIO_ID=".$_SESSION['userId']."
												AND ACE_BLOQUEADO != 1";
											}

											if ($temChamadoAtrasado) $sql .= " AND OCOR_DTPRAZO < '".date('Y-m-d')."' ";

											if (isset($_GET['filtroEncerrado']) || ($_SESSION['filtroConsultor']!= 0 || $_SESSION['filtroCliente'] != 0) )
											{

												if (isset($_GET['filtroModulo']))
													$modulo = $_GET['filtroModulo'];
												else
													$modulo = "";
												if (isset($_GET['filtroEncerrado']))
													$encerrado = $_GET['filtroEncerrado'];
												else
													$encerrado = "";
												$consultor = $_SESSION['filtroConsultor'];
												$cliente   = $_SESSION['filtroCliente'];

												if (isset($_GET['filtroConsultor']) && $_GET['filtroConsultor'] != "")
													$consultor = $_GET['filtroConsultor'];
												if (isset($_GET['filtroCliente']) && $_GET['filtroCliente'] != "")
													$cliente = $_GET['filtroCliente'];

												if ($consultor != 0 && $consultor != "")
													$sql .= " AND OCOR_CONSULTOR=".$consultor;

												if ($cliente != 0 && $cliente != "")
													$sql .= " AND OCOR_ID_CLIENTE=".$cliente;

												if ($modulo != "")
													$sql .= " AND OCOR_ID_MODULOS=".$modulo;

												if ($encerrado == 'SIM')
													$sql .= " AND OCOR_DTENCERRAMENTO != 0";
												else if ($encerrado == 'NÃO')
													$sql .= " AND OCOR_DTENCERRAMENTO = 0";



											}

											if($_SESSION['userNivel'] == 'EXTERNO')
											{
												if(!isset($_GET['filtroEncerrado']) || $_GET['filtroEncerrado'] == "")
												{
													$sql = "
													SELECT DISTINCT OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_ID_USUARIO, OCOR_ID_CLIENTE, OCOR_DTENCERRAMENTO, OCOR_CONSULTOR, OCOR_DESCRICAO, OCOR_ID_PROB, OCOR_DTINCLUSAO, OCOR_ID_MODULOS, OCOR_CONSULTOR, OCOR_DTPRAZO, OCOR_CONTATO, OCOR_CLASSIFICACAO
													FROM OCORRENCIAS
													INNER JOIN USUARIOS_X_CLIENTES
													ON USERXCLI_CLIENTE = OCOR_ID_CLIENTE
													INNER JOIN USUARIO
													ON USER_ID = USERXCLI_ID_USUARIO
													INNER JOIN ACESSOS
													ON ACE_USUARIO_ID = USER_ID
													AND ACE_ID_MODULOS = OCOR_ID_MODULOS
													WHERE
													OCOR_ID_CLIENTE = ".$_SESSION['userEmpresa']."
													AND USERXCLI_ID_USUARIO = ".$_SESSION['userId']."
													AND OCOR_DTENCERRAMENTO = 0
													AND ACE_BLOQUEADO != 1";

												}else{
													$sql = "
													SELECT DISTINCT OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_ID_USUARIO, OCOR_ID_CLIENTE, OCOR_DTENCERRAMENTO, OCOR_CONSULTOR, OCOR_DESCRICAO, OCOR_ID_PROB, OCOR_DTINCLUSAO, OCOR_ID_MODULOS, OCOR_CONSULTOR, OCOR_DTPRAZO, OCOR_CONTATO, OCOR_CLASSIFICACAO
													FROM OCORRENCIAS
													INNER JOIN USUARIOS_X_CLIENTES
													ON USERXCLI_CLIENTE = OCOR_ID_CLIENTE
													INNER JOIN  USUARIO
													ON USER_ID = USERXCLI_ID_USUARIO
													INNER JOIN ACESSOS
													ON ACE_USUARIO_ID = USER_ID
													AND ACE_ID_MODULOS = OCOR_ID_MODULOS
													WHERE
													OCOR_ID_CLIENTE = ".$_SESSION['userEmpresa']."
													AND USERXCLI_ID_USUARIO = ".$_SESSION['userId']."
													AND ACE_BLOQUEADO != 1 ";

												}

												if ($temChamadoAtrasado) $sql .= " AND OCOR_DTPRAZO < '".date('Y-m-d')."' ";

												if (isset($_GET['filtroEncerrado']) || ($_SESSION['filtroConsultor']!= 0 || $_SESSION['filtroCliente'] != 0) )
												{

													if (isset($_GET['filtroModulo']))
														$modulo = $_GET['filtroModulo'];
													else
														$modulo = "";
													if (isset($_GET['filtroEncerrado']))
														$encerrado = $_GET['filtroEncerrado'];
													else
														$encerrado = "";
													$consultor = $_SESSION['filtroConsultor'];
													$cliente   = $_SESSION['filtroCliente'];

													if (isset($_GET['filtroConsultor']) && $_GET['filtroConsultor'] != "")
														$consultor = $_GET['filtroConsultor'];
													if (isset($_GET['filtroCliente']) && $_GET['filtroCliente'] != "")
														$cliente = $_GET['filtroCliente'];

													if ($consultor != 0 && $consultor != "")
														$sql .= " AND OCOR_CONSULTOR=".$consultor;

													if ($cliente != 0 && $cliente != "")
														$sql .= " AND OCOR_ID_CLIENTE=".$cliente;

													if ($modulo != "")
														$sql .= " AND OCOR_ID_MODULOS=".$modulo;

													if ($encerrado == 'SIM')
														$sql .= " AND OCOR_DTENCERRAMENTO != 0";
													else if ($encerrado == 'NÃO')
														$sql .= " AND OCOR_DTENCERRAMENTO = 0";
												}

											}
										}
					//se eh admin busca tudo
										if($_SESSION['userNivel'] == 'ADMINISTRADOR')
										{

											if(!isset($_GET['filtroEncerrado']) || $_GET['filtroEncerrado'] == "")
											{
												$sql = "SELECT * FROM ocorrencias WHERE OCOR_DTENCERRAMENTO = 0 ";
											}else{
												$sql = "SELECT * FROM ocorrencias WHERE OCOR_ID > 0 ";
											}


											if (isset($_GET['filtroEncerrado']) || ($_SESSION['filtroConsultor']!= 0 || $_SESSION['filtroCliente'] != 0) )
											{

												if (isset($_GET['filtroModulo']))
													$modulo = $_GET['filtroModulo'];
												else
													$modulo = "";
												if (isset($_GET['filtroEncerrado']))
													$encerrado = $_GET['filtroEncerrado'];
												else
													$encerrado = "";
												$consultor = $_SESSION['filtroConsultor'];
												$cliente   = $_SESSION['filtroCliente'];

												if (isset($_GET['filtroConsultor']) && $_GET['filtroConsultor'] != "")
													$consultor = $_GET['filtroConsultor'];
												if (isset($_GET['filtroCliente']) && $_GET['filtroCliente'] != "")
													$cliente = $_GET['filtroCliente'];

												if ($consultor != 0 && $consultor != "")
													$sql .= " AND OCOR_CONSULTOR=".$consultor;

												if ($cliente != 0 && $cliente != "")
													$sql .= " AND OCOR_ID_CLIENTE=".$cliente;

												if ($modulo != "")
													$sql .= " AND OCOR_ID_MODULOS=".$modulo;

												if ($encerrado == 'SIM')
													$sql .= " AND OCOR_DTENCERRAMENTO != 0";
												else if ($encerrado == 'NÃO')
													$sql .= " AND OCOR_DTENCERRAMENTO = 0";
											}

										}

/*										
												<th>DESCRICAO IMPACTO</th>
*/

										$sql .= " ORDER BY OCOR_DTENCERRAMENTO";
										$result = $db->query($sql);

										echo "<table id=\"datatable-responsive\" class=\"table table-striped table-bordered dt-responsive nowrap\" cellspacing=\"0\" width=\"100%\">
										<thead>
											<tr>
												<th>DATA ABERTURA</th>
												<th>ID OCORRENCIA</th>
												<th>DESCRIÇÃO RESUMIDA</th>
												<th>EMPRESA</th>
												<th>MÓDULO</th>
												<th>PROBLEMA</th>
												<th>CONSULTOR</th>
												<th>PRAZO</th>
												<th>CONTATO</th>
												<th>STATUS</th>
												<th>FECHAMENTO</th>
												<th>TEMPO GASTO</th>
												<th>CLASSIFICAÇÃO</th>
 												<th>IMPACTO</th>
 												<th>DESCRICAO IMPACTO</th>
												<th>APONTAMENTOS</th>
												<th>EDIÇÃO</th>

											</tr>
										</thead>
										<tbody>";
					if($result){
						while ($row = $result->fetch_assoc()){
							$finalPrazo = $row['OCOR_DTPRAZO'];

							include_once "ChecaAtraso.class.php";
							$checaAtraso = new ChecaAtraso();
							if($checaAtraso->checaAtrasoChamado()) $temChamadoAtrasado = true;
							else $temChamadoAtrasado = false;

							if ($checaAtraso->ehDataAtrasada($finalPrazo)) $chamadoAtrasado = true;
							else $chamadoAtrasado = false;

							// define bloqueio de assentamento
							$bloqueioEditar = false;
							if ($temChamadoAtrasado && !$chamadoAtrasado) $bloqueioEditar = true;

							// cada while eh uma ocorrencia
							$classificacao = '';
							if($row['OCOR_CLASSIFICACAO'] == 1)
								$classificacao = 'HELPDESK';
							if($row['OCOR_CLASSIFICACAO'] == 2)
								$classificacao = 'IMPLANTAÇÃO';
							if($row['OCOR_CLASSIFICACAO'] == 3)
								$classificacao = 'DESENVOLVIMENTO';

							
							$impacto = '';
							if($row['OCOR_IMPACTO'] == 1)
								$impacto = 'BAIXA';
							if($row['OCOR_IMPACTO'] == 2)
								$impacto = 'BAIXA';
							if($row['OCOR_IMPACTO'] == 3)
								$impacto = 'MEDIA';
							if($row['OCOR_IMPACTO'] == 4)
								$impacto = 'ALTA';

							$impactoDC = '';
							if($row['OCOR_IMPACTO'] == 1)
								$impactoDC = '1-DUVIDA NO PRODUTO';
							if($row['OCOR_IMPACTO'] == 2)
								$impactoDC = '2-ERRO C/PROC.PALIATIVO';
							if($row['OCOR_IMPACTO'] == 3)
								$impactoDC = '3-ERRO ATIV.NAO.CRITICA';
							if($row['OCOR_IMPACTO'] == 4)
								$impactoDC = '4-ERRO S/PROC.PALEATIVO';

							
							$idOcorrencia = $row['OCOR_ID'];
							// fazer somatorio das horas
							$somatorioMinutos = 0;
							$tempo = 0;
							$somatorioTotal = "00:00";
							$sqlHrs = "SELECT rateio_hrref FROM rateio WHERE rateio_chamado=".$idOcorrencia;

							$resultHrs = mysqli_query($db, $sqlHrs);

							if ($resultHrs){
							while ($rowHrs = $resultHrs->fetch_assoc()) {
								$tempo = substr($rowHrs['rateio_hrref'], 0, 5);
								$vectorTempo = explode(":", $tempo);
								$horas = $vectorTempo[0];
								$minutos = $vectorTempo[1];
								$somatorioMinutos += (int) $minutos + $horas * 60;
								}
							}
							$hrAux = 0;
							while ($somatorioMinutos > 60) {
								$hrAux ++;
								$somatorioMinutos -= 60;
							}

							if ($hrAux < 10) $hrAux = "0".$hrAux;
							if ($somatorioMinutos < 10) $somatorioMinutos = "0".$somatorioMinutos;

							$somatorioTotal = $hrAux.":".$somatorioMinutos;

							if ($row['OCOR_DTENCERRAMENTO'] != 0)
							{
								$textoBtn = "Visualizar";
								$linha = "<tr style=\"color: green; background: #bbffaa;\">";
								$status = 'ENCERRADO';
								$fechamento = $row['OCOR_DTENCERRAMENTO'];
							}
							else
							{
								$textoBtn = "Apontamentos";
								$linha = "<tr>";
								$status = 'ABERTO';
								$fechamento = '';
							}

							if ($bloqueioEditar) $linha = "<tr style=\"background-color: #ff8989; color: white\">";


							echo $linha;
							echo "<td>". $row['OCOR_DTINCLUSAO']."</td>";
							echo "<td>" . $row['OCOR_ID'] . "</td>";

							echo "<td>" . $row['OCOR_DESC_RESUMIDA'] . "</td>";

							$sql = "SELECT CLI_NREDUZ FROM CLIENTES WHERE CLI_ID=".$row['OCOR_ID_CLIENTE'];
							$resultAux = mysqli_query($db, $sql);
							$resultAux = $resultAux->fetch_assoc();
							$nomeCliente = $resultAux['CLI_NREDUZ'];

							echo "<td>". $nomeCliente ."</td>";

							$sql = "SELECT MOD_DESC_REDUZ FROM MODULOS WHERE MOD_ID=".$row['OCOR_ID_MODULOS'];
							$resultAux = mysqli_query($db, $sql);
							$resultAux = $resultAux->fetch_assoc();
							$nomeModulo = $resultAux['MOD_DESC_REDUZ'];

							echo "<td>". $nomeModulo ."</td>";

							$sql = "SELECT PROB_DESCRICAO FROM CADASTRO_PROBLEMAS WHERE PROB_ID=".$row['OCOR_ID_PROB'];
							$resultAux = mysqli_query($db, $sql);
							$resultAux = $resultAux->fetch_assoc();
							$nomeProblema = $resultAux['PROB_DESCRICAO'];

							echo "<td>". $nomeProblema."</td>";

							if($row['OCOR_CONSULTOR'] == ''){
								$idConsultor = 1;
							}
							else
								$idConsultor = $row['OCOR_CONSULTOR'];

							$sql = "SELECT USER_NOME FROM USUARIO WHERE USER_ID=".$idConsultor;
							$resultAux = mysqli_query($db, $sql);
							$resultAux = $resultAux->fetch_assoc();
							$nomeConsultor = $resultAux['USER_NOME'];

							echo "<td>". $nomeConsultor."</td>";
							echo "<td>". $row['OCOR_DTPRAZO']."</td>";
							echo "<td>". $row['OCOR_CONTATO']."</td>";
							echo "<td>". $status."</td>";
							echo "<td>". $fechamento."</td>";
							echo "<td>".$somatorioTotal."</td>";
							echo "<td>".$classificacao."</td>";
							echo "<td>".$impacto."</td>";
							echo "<td>".$impactoDC."</td>";

							$disabled = "";
							if ($bloqueioEditar) $disabled = "disabled";
							echo "<td>
							<form method=post action =\"assentamento.php?idocorr=".$row['OCOR_ID']."\">
								<button id=".$row['OCOR_ID']." type=\"submit\" class=\"btn btn-info btn-xs\" ".$disabled."><i class=\"fa fa-pencil\"></i> ".$textoBtn." </button>
							</form>
						</td>";


						if (($row['OCOR_DTENCERRAMENTO'] != 0 || $bloqueioEditar) && $_SESSION['userNivel'] != 'ADMINISTRADOR')
						{
							echo "<td>
							<button id=".$row['OCOR_ID']." type=\"submit\" class=\"btn btn-info btn-xs\" disabled><i class=\"fa fa-pencil\"></i> Editar </button>
						</td>";
						}
						else
						{
							echo "<td>
							<form method=post action =\"ocorrencias.php?id=".$row['OCOR_ID']."\">
								<button id=".$row['OCOR_ID']." type=\"submit\" onclick=\"popularUpdate(".$row['OCOR_ID'].")\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i> Editar </button>
							</form>
						</td>";
					}


				echo '</tr>';
			}
			$result->free();
		}
		echo 		"</tbody>
	</table>";
	$db->close();
							?>
						</div>
					</div>
					<script type="text/javascript">
						function popularUpdate (id)
						{
							document.getElementById('idUpdate').value = id;
						}
					</script>
					<!-- FIM TABELA -->
					<!-- NÃO REMOVER -->
					<div class="x_content">
						<!-- start form for validation -->
						<form id="demo-form" data-parsley-validate>
						</form>
						<!-- end form for validations -->
					</div>

				</div>
				<!-- /RIGHT COL-->
			</div>
		</div>
		<!-- FIM DA PÁGINA DE CONTEÚDO -->
	</div>
</div>
<!-- FOOTER -->
<footer>
	<div class="pull-right">
		Compacta Dashboard desenvolvida por <a href="http://www.solucaocompacta.com.br">Solução Compacta</a>
	</div>
	<div class="clearfix"></div>
</footer>
<!-- /FOOTER -->
</div>
<script>
	function limparIdEnd()
	{
		document.getElementById('idOcorrEnd').value = 0;
	}
</script>
<script>
	function setIdEnd(id)
	{
		document.getElementById('idOcorrEnd').value = id;
	}
</script>

<!-- FIM DO MODAL -->
</div>


<!-- jQuery -->
<script src="../vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="../vendors/nprogress/nprogress.js"></script>
<!-- iCheck -->
<script src="../vendors/iCheck/icheck.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="../vendors/moment/min/moment.min.js"></script>
<script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap-wysiwyg -->
<script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
<script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
<script src="../vendors/google-code-prettify/src/prettify.js"></script>
<!-- jQuery Tags Input -->
<script src="../vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
<!-- Parsley -->
<script src="../vendors/parsleyjs/dist/parsley.js"></script>
<!-- Autosize -->
<script src="../vendors/autosize/dist/autosize.min.js"></script>
<!-- jquery.inputmask -->
<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
<!-- Datatables -->
<script src="../vendors/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="../vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
<script src="../vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
<script src="../vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
<script src="../vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
<script src="../vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
<script src="../vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
<script src="../vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
<script src="../vendors/datatables.net-scroller/js/datatables.scroller.min.js"></script>
<script src="../vendors/jszip/dist/jszip.min.js"></script>
<script src="../vendors/pdfmake/build/pdfmake.min.js"></script>
<script src="../vendors/pdfmake/build/vfs_fonts.js"></script>
<!-- jQuery autocomplete -->
<script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
<!-- starrr -->
<script src="../vendors/starrr/dist/starrr.js"></script>
<!-- Select2 -->
<script src="../vendors/select2/dist/js/select2.full.min.js"></script>

<!-- Custom Theme Scripts -->
<script src="../build/js/custom.min.js"></script>

<!-- bootstrap-daterangepicker -->
<script>
	$(document).ready(function() {
		$('#birthday').daterangepicker({
			singleDatePicker: true,
			calender_style: "picker_4"
		}, function(start, end, label) {
			console.log(start.toISOString(), end.toISOString(), label);
		});
	});
</script>
<!-- /bootstrap-daterangepicker -->

<!-- bootstrap-wysiwyg -->
<script>
	$(document).ready(function() {
		function initToolbarBootstrapBindings() {
			var fonts = ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier',
			'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Lucida Sans', 'Tahoma', 'Times',
			'Times New Roman', 'Verdana'
			],
			fontTarget = $('[title=Font]').siblings('.dropdown-menu');
			$.each(fonts, function(idx, fontName) {
				fontTarget.append($('<li><a data-edit="fontName ' + fontName + '" style="font-family:\'' + fontName + '\'">' + fontName + '</a></li>'));
			});
			$('a[title]').tooltip({
				container: 'body'
			});
			$('.dropdown-menu input').click(function() {
				return false;
			})
			.change(function() {
				$(this).parent('.dropdown-menu').siblings('.dropdown-toggle').dropdown('toggle');
			})
			.keydown('esc', function() {
				this.value = '';
				$(this).change();
			});

			$('[data-role=magic-overlay]').each(function() {
				var overlay = $(this),
				target = $(overlay.data('target'));
				overlay.css('opacity', 0).css('position', 'absolute').offset(target.offset()).width(target.outerWidth()).height(target.outerHeight());
			});

			if ("onwebkitspeechchange" in document.createElement("input")) {
				var editorOffset = $('#editor').offset();

				$('.voiceBtn').css('position', 'absolute').offset({
					top: editorOffset.top,
					left: editorOffset.left + $('#editor').innerWidth() - 35
				});
			} else {
				$('.voiceBtn').hide();
			}
		}

		function showErrorAlert(reason, detail) {
			var msg = '';
			if (reason === 'unsupported-file-type') {
				msg = "Unsupported format " + detail;
			} else {
				console.log("error uploading file", reason, detail);
			}
			$('<div class="alert"> <button type="button" class="close" data-dismiss="alert">&times;</button>' +
				'<strong>File upload error</strong> ' + msg + ' </div>').prependTo('#alerts');
		}

		initToolbarBootstrapBindings();

		$('#editor').wysiwyg({
			fileUploadError: showErrorAlert
		});

		window.prettyPrint;
		prettyPrint();
	});
</script>
<!-- /bootstrap-wysiwyg -->

<!-- jQuery Tags Input -->
<script>
	function onAddTag(tag) {
		alert("Added a tag: " + tag);
	}

	function onRemoveTag(tag) {
		alert("Removed a tag: " + tag);
	}

	function onChangeTag(input, tag) {
		alert("Changed a tag: " + tag);
	}

	$(document).ready(function() {
		$('#tags_1').tagsInput({
			width: 'auto'
		});
	});
</script>
<!-- /jQuery Tags Input -->

<!-- Parsley -->
<script>
	$(document).ready(function() {
		$.listen('parsley:field:validate', function() {
			validateFront();
		});
		$('#demo-form .btn').on('click', function() {
			$('#demo-form').parsley().validate();
			validateFront();
		});
		var validateFront = function() {
			if (true === $('#demo-form').parsley().isValid()) {
				$('.bs-callout-info').removeClass('hidden');
				$('.bs-callout-warning').addClass('hidden');
			} else {
				$('.bs-callout-info').addClass('hidden');
				$('.bs-callout-warning').removeClass('hidden');
			}
		};
	});

	$(document).ready(function() {
		$.listen('parsley:field:validate', function() {
			validateFront();
		});
		$('#demo-form2 .btn').on('click', function() {
			$('#demo-form2').parsley().validate();
			validateFront();
		});
		var validateFront = function() {
			if (true === $('#demo-form2').parsley().isValid()) {
				$('.bs-callout-info').removeClass('hidden');
				$('.bs-callout-warning').addClass('hidden');
			} else {
				$('.bs-callout-info').addClass('hidden');
				$('.bs-callout-warning').removeClass('hidden');
			}
		};
	});
	try {
		hljs.initHighlightingOnLoad();
	} catch (err) {}
</script>
<!-- /Parsley -->

<!-- Autosize -->
<script>
	$(document).ready(function() {
		autosize($('.resizable_textarea'));
	});
</script>
<!-- /Autosize -->

<!-- jquery.inputmask -->
<script>
	$(document).ready(function() {
		$(":input").inputmask();
	});
</script>
<!-- /jquery.inputmask -->

<!-- Datatables -->
<script>
	$(document).ready(function() {
		var handleDataTableButtons = function() {
			if ($("#datatable-responsive").length) {
				$("#datatable-responsive").DataTable({
					dom: "Bfrtip",
					buttons: [
					 'copyHtml5',
					 'excel',
					 'csvHtml5',
					 'print'
			 ],
					responsive: true,
					'ordering': false
				});
			}
		};

		TableManageButtons = function() {
			"use strict";
			return {
				init: function() {
					handleDataTableButtons();
				}
			};
		}();

		TableManageButtons.init();
	});
</script>
<!-- /Datatables -->

<!-- Select2 -->
<script>
	$(document).ready(function() {
		$(".select2_single").select2({
			placeholder: "...",
			allowClear: true
		});
		$(".select2_group").select2({});
		$(".select2_multiple").select2({
			maximumSelectionLength: 4,
			placeholder: "Limite de seleção é 4",
			allowClear: true
		});
	});
</script>
<!-- /Select2 -->

<!-- Limpar -->
<script>
	function limpar()
	{
		document.getElementById('cliente').value = "";
		document.getElementById('problema').value = "";
		document.getElementById('modulo').value = "";
		document.getElementById('descricaoReduz').value = "";
		document.getElementById('descricao').value = "";
	}
</script>
<!-- /Limpar -->

<!-- Starrr -->
<script>
	$(document).ready(function() {
		$(".stars").starrr();
		$(".stars2").starrr();
		$(".stars3").starrr();

		$('.stars-existing').starrr({
			rating: 4
		});

		$('.stars').on('starrr:change', function (e, value) {
			$('.stars-count').html(value);
			document.getElementById('atendimentoAv').value = value;
		});

		$('.stars2').on('starrr:change', function (e, value) {
			$('.stars-count2').html(value);
			document.getElementById('prazoAv').value = value;
		});

		$('.stars3').on('starrr:change', function (e, value) {
			$('.stars-count3').html(value);
			document.getElementById('expectativasAv').value = value;
		});

		$('.stars-existing').on('starrr:change', function (e, value) {
			$('.stars-count-existing').html(value);
		});
	});
</script>
<!-- /Starrr -->

<!-- COMBOS RELACIONADAS-->
<script type="text/javascript">
	$(document).ready(function() {
		$('#problema')
		.attr('disabled', true);

		$('#modulo').on('change', function() {
			var idModulo = $('#modulo').val();
			<?php if (isset($_GET['id'])) $get = 1; else $get = 0; ?>
			var get = "<?php echo $get?>";

			$.ajax({
				type: 'POST',
				url: 'combos.php',
				data: {
					modulo: idModulo
				},
				success: function(result){
					$('#problema')
					.empty()
					.append(result);

					if (get == 0)
						$('#problema').attr('disabled',false);
				},
				error: function(){
					alert('Erro ao requisitar valores');
				}
			});

		});
	});
</script>

</body>
</html>
<!-- puxa campos para update -->
<?php
if (isset($_GET))
{
	/*Mantem valores no campo de filtro ao dar reload*/
	if (isset($_GET['filtroEncerrado']))
	{
		echo "<script>
		$('#filtroCliente').val('".$_GET['filtroCliente']."');
		$('#filtroConsultor').val('".$_GET['filtroConsultor']."');
		$('#filtroEncerrado').val('".$_GET['filtroEncerrado']."');
		$('#filtroModulo').val('".$_GET['filtroModulo']."');
	</script>";
}
else if (isset($_GET['filtroEncerrado']) && $_SESSION['userNivel']  == "USUÁRIO")
{
	echo "<script>
	$('#filtroEncerrado').val('".$_GET['filtroEncerrado']."');
</script>";
}

	//puxar valores para os campos
if (isset($_GET['id']))
{
	$id = $_GET['id'];

	include_once "../scripts/Conexao-class.php";
	$conect = new Conexao();
	$db = $conect->getLink();
	$sql = "SELECT * FROM ocorrencias WHERE OCOR_ID=".$id;
	$result = $db->query($sql);
	$dados = $result->fetch_assoc();

	#CLIENTE
	echo "<script> $(document).ready(function ()
	{
		$(\"#cliente\").val('".$dados['OCOR_ID_CLIENTE']."').change();
	});
</script>";

	#CONTATO
echo "<script> document.getElementById('contato').value        = '".$dados['OCOR_CONTATO']."'; </script>";


#MODULO
echo "<script> $(document).ready(function ()
{
	$(\"#modulo\").val('".$dados['OCOR_ID_MODULOS']."').change();
	$('#modulo').prop('readonly', true);
});
</script>";

	#PROBLEMA
echo "<script> $(document).ready(function ()
{
	$(\"#problema\").val('".$dados['OCOR_ID_PROB']."').change();
	$('#problema').prop('readonly', true);
});
</script>";

$descricao = $dados['OCOR_DESCRICAO'];
$descricao = str_replace("'", "\'", $descricao);
$descricaoResumida = $dados['OCOR_DESC_RESUMIDA'];
$descricaoResumida = str_replace("'", "\'", $descricaoResumida);

	#DESCRICAO
echo "<script> document.getElementById('descricao').value        = '".$descricao."'; </script>";

	#DESCRICAO RESUMIDA
echo "<script> document.getElementById('descricaoReduz').value   = '".$descricaoResumida."'; </script>";

	#IMPACTO
echo "<script> document.getElementById('impacto').value   = '".$dados['OCOR_IMPACTO']."'; </script>";

	#CLASSIFICAÇÃO
echo "<script> document.getElementById('classificacao').value   = '".$dados['OCOR_CLASSIFICACAO']."'; </script>";

echo "<script> document.getElementById('idUpdate').value         = '".$id."'; </script>";

echo "<script>
$('#modulo').attr('disabled',true);
$('#problema').attr('disabled',true);
$('#descricaoReduz').attr('disabled',true);
$('#descricao').attr('disabled',true);
$('#cliente').attr('disabled',true);
$('#contato').attr('disabled',true);

</script>";
mysqli_free_result($result);
$conect->fechar();
}
}
exit;
?>
