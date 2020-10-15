	<?php
	if (!isset($_SESSION)) session_start();

	include_once "../scripts/Conexao-class.php";
	$conect = new Conexao();
	$link = $conect->getLink();
	
	//se eh usuario verificar se a ocorrencia possui o id do usuario
	if($_SESSION['userNivel'] == 'USUÁRIO') {
		$sql = "SELECT OCOR_ID_CLIENTE FROM ocorrencias WHERE OCOR_ID=".$_GET['idocorr'];

		$result = mysqli_query($link, $sql);
		$ocorrencia = $result->fetch_assoc();
		$cliente = $ocorrencia['OCOR_ID_CLIENTE'];

		$sql = "SELECT * FROM usuario
		INNER JOIN usuarios_x_clientes
		ON USERXCLI_ID_USUARIO = USER_ID
		WHERE 
		USER_ID = ".$_SESSION['userId']."
		AND USERXCLI_CLIENTE = ".$cliente;

		$resultAux = mysqli_query($link, $sql);

		if (mysqli_num_rows($resultAux) < 1)
		{
			echo "<script> window.location.replace('home.php'); </script>";
			exit;
		}
	}
	else
	{
	//se eh consultor verificar se o mesmo tem acesso ao módulo da ocorrencia
		$sql = "SELECT OCOR_ID_MODULOS FROM ocorrencias WHERE OCOR_ID=".$_GET['idocorr'];
		$result = mysqli_query($link, $sql);
		$modulo = $result->fetch_assoc();

		$sqlAux = "SELECT * FROM acessos WHERE ACE_USUARIO_ID=".$_SESSION['userId']." AND ACE_BLOQUEADO != 1";
		$resultAux = mysqli_query($link, $sqlAux);

		$usuarioAcesso = false;
		while ($acesso = $resultAux->fetch_assoc())
		{
			if($modulo['OCOR_ID_MODULOS'] == $acesso['ACE_ID_MODULOS']){
				$usuarioAcesso = true;
			}
		}

		if (!$usuarioAcesso) {
			echo "<script> window.location.replace('home.php'); </script>";
			exit;
		}
	}
	
	
	if (isset($_GET['idocorr'])) $id = $_GET['idocorr'];

	if (!isset($_GET['idocorr']) || $_GET['idocorr'] < 1)
	{
		echo "<script> window.location.replace('ocorrencias.php'); </script>";
		exit;
	}

	// somatorio de horas para este chamado
	$sql = "SELECT * FROM rateio WHERE rateio_chamado=".$id;
	$result = mysqli_query($link, $sql);
	$hrAux = 0;
	$minutosTotais = 0;
	while ($row = $result->fetch_assoc()) {
		$hrref = substr($row['rateio_hrref'], 0, 5);
		$horaVetor = explode(":", $hrref);

		$minutosTotais += (int) $horaVetor[0] * 60 + $horaVetor[1];
		while ($minutosTotais >= 60) {
			$minutosTotais -= 60;
			$hrAux++;
		}
	}
	if ($hrAux < 10) $hrAux = "0".$hrAux;
	if ($minutosTotais < 10) $minutosTotais = "0".$minutosTotais;
	$stringHorasChamado = "Total de horas trabalhadas na ocorrência: ".$hrAux."h".$minutosTotais."min";

	if ($_SESSION['userNivel'] != 'ADMINISTRADOR' ) $stringHorasChamado = " ";
	
	
	$sql = "SELECT * FROM ocorrencias WHERE OCOR_ID=".$id;
	$result = mysqli_query($link, $sql);
	$valores = $result->fetch_assoc();

	$descReduz   = $valores['OCOR_DESC_RESUMIDA'];
	$descricao   = $valores['OCOR_DESCRICAO'];
	$projeto     = $valores['OCOR_PROJETO'];
	$sqlProj     = "SELECT PROJ_DESCRICAO FROM cadastro_projetos WHERE  PROJ_ID=".$projeto;
	$resultProj  = mysqli_query($link, $sqlProj);
	$nomeProjeto = $resultProj->fetch_assoc();
	$nomeProjeto = $nomeProjeto['PROJ_DESCRICAO'];

	$descricao = str_replace("&#13;","<br/>",$descricao);
	$descricao = str_replace("&#13;&#13;","<br/><br/>",$descricao);
	$prazo = $valores['OCOR_DTPRAZO'];
	$finalPrazo = $prazo;
	//$dataInclusao = $valores['OCOR_DTINCLUSAO'];
	$dataInclusao = "Data da inclusão: ".$valores['OCOR_DTINCLUSAO'];

	/*
	$stringHorasChamado = "Total de horas trabalhadas na ocorrência: ".$hrAux."h".$minutosTotais."min";
*/

	
	// dados do solicitante
	$solicitante = $valores['OCOR_ID_USUARIO'];
	$sql = "SELECT USER_NOME FROM USUARIO WHERE USER_ID=".$solicitante;
	$resultAux = mysqli_query($link, $sql);
	$solicitante = $resultAux->fetch_assoc();
	$solicitante = $solicitante['USER_NOME'];

	$contato = $valores['OCOR_CONTATO'];

	// dados do consultor atual da ocorrencia
	$ocorrenciaConsultor = $valores['OCOR_CONSULTOR'];
	$sql = "SELECT USER_NOME FROM USUARIO WHERE USER_ID=".$ocorrenciaConsultor;
	$resultAux = mysqli_query($link, $sql);
	$data = $resultAux->fetch_assoc();
	$nomeConsultorOcor = $data['USER_NOME'];


	include_once "../scripts/ConversorData-class.php";
	$conversorData = new ConversorData;
	$prazo = $conversorData->sql2Brasil($prazo);

	//vem no padrao sql
	$prazoLimiteSql = $valores['OCOR_DTLIMITE'];
	//no padrao br
	$prazoLimite = $conversorData->sql2Brasil($prazoLimiteSql);

	$idUsuario = $valores['OCOR_ID_USUARIO'];
	$idCliente = $valores['OCOR_ID_CLIENTE'];
	$idModulo = $valores['OCOR_ID_MODULOS'];
	$impacto = $valores['OCOR_IMPACTO'];
	$encerramentoData = $valores['OCOR_DTENCERRAMENTO'];
	$sql = "SELECT MOD_DESC_REDUZ FROM modulos WHERE MOD_ID=".$idModulo;
	$result = mysqli_query($link, $sql);
	$valores = $result->fetch_assoc();
	$modulo = $valores['MOD_DESC_REDUZ'];
	$nomeModulo = $modulo;
	$sql = "SELECT USER_NOME, USER_LOGIN FROM usuario WHERE USER_ID=".$idUsuario; 
	$result = mysqli_query($link, $sql);
	$valores = $result->fetch_assoc();

	$usuario = $valores['USER_NOME'];
	$emailUsuario = $valores['USER_LOGIN'];

	$sql = "SELECT CLI_NOME,CLI_TEL FROM clientes WHERE CLI_ID=".$idCliente; 
	$result = mysqli_query($link, $sql);
	$valores = $result->fetch_assoc();
	$cliente = $valores['CLI_NOME'];
	$telefone = $valores['CLI_TEL'];
	$conect->fechar();

	// import checa atraso
	include_once "ChecaAtraso.class.php";
	$checaAtraso = new ChecaAtraso();

	// checa prazo limite atrasado
	$prazoLimiteAtrasado = false;
	if ($prazoLimiteSql != '0000-00-00')
		if ($checaAtraso->ehDataAtrasada($prazoLimiteSql)) 
			$prazoLimiteAtrasado = true;

	//testes nova funcao
	if($checaAtraso->checaAtrasoChamado()) $temChamadoAtrasado = true;
	else $temChamadoAtrasado = false;

	if ($checaAtraso->ehDataAtrasada($finalPrazo)) $chamadoAtrasado = true;
	else $chamadoAtrasado = false;

	// define bloqueio de assentamento
	$bloqueioSalvar = false;
	if ($temChamadoAtrasado && !$chamadoAtrasado) $bloqueioSalvar = true;

	if ($checaAtraso->checaAtrasoOS() && !$chamadoAtrasado) $bloqueioSalvar = true;

	if ($bloqueioSalvar) {
		echo "<script>alert('Você não pode assentar chamados atuais enquanto houverem chamados atrasados não reprogramados ou ordens de serviço pendentes');</script>";
		$mensagem = 'Você não pode assentar chamados atuais enquanto houverem chamados atrasados não reprogramados ou ordens de serviço pendentes';
		echo "<script> window.location.replace('home.php')</script>";
	}

	else $mensagem = "<small>Últimas atualizações sobre a ocorrência</small>";
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
			ul.arquivos {

			}

			ul.arquivos a li {
				color: white;
			}

			ul.arquivos li {
				width: 20%;
				height: auto;
				font-size: 105%;
				padding: 5px;
				margin: 5px;
				float: left;
				cursor: pointer;
				border: 1px solid transparent;
				border-radius: 5px;

				list-style-type: none;
				background: #1e5799; /* Old browsers */
				background: -moz-linear-gradient(top, #1e5799 0%, #2989d8 50%, #207cca 51%, #7db9e8 100%); /* FF3.6-15 */
				background: -webkit-linear-gradient(top, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%); /* Chrome10-25,Safari5.1-6 */
				background: linear-gradient(to bottom, #1e5799 0%,#2989d8 50%,#207cca 51%,#7db9e8 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
			}

			ul.arquivos li:hover{
				background: darkblue;
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

				<!-- page content -->
				<div class="right_col" role="main">
					<div class="">
						<div class="page-title">
							<div class="title_left" style="width: 100%;">
								<h3>Assentamentos <?php echo $mensagem?></h3>
							</div>
						</div>

						<div class="clearfix"></div>

						<div class="row">
							<div class="col-md-12">
								<div class="x_panel">
									<div class="x_title">
										<h2>Ticket (<?php echo $_GET['idocorr']?>) <small>detalhes e assentamentos</small></h2>
										<div class="clearfix"></div>
									</div>
									<div class="x_content">
										<section class="content invoice">
											<!-- title row -->
											<div class="row">
												<div class="col-xs-12 invoice-header">
													<h1>
														<i class="fa fa-globe"></i> <?php echo $descReduz;?> 
														<small>(Módulo: <?php echo $modulo; ?>)</small> <br>
														<h2><?php echo $dataInclusao;?></h2>
														
														<h2><?php echo $stringHorasChamado ?></h2>
														<?php 
														
														include_once "../scripts/Conexao-class.php";
														$conect = new Conexao();
														$link = $conect->getLink();
														
														
														$sql = "SELECT OCOR_DTENCERRAMENTO, OCOR_USUARIO_ENCERROU FROM ocorrencias WHERE OCOR_ID=".$_GET['idocorr'];
														$result = mysqli_query($link, $sql);
														$encerramento = $result->fetch_assoc();
														$encerrado = false;
	if ($encerramento['OCOR_DTENCERRAMENTO'] != 0)
	{	
		# busca usuario que encerrou
		$msg_usuario = "";
		$usuario = $encerramento['OCOR_USUARIO_ENCERROU'];
		if ($usuario != 0) {
			$sqlUser = "SELECT USER_NOME, USER_LOGIN
						FROM usuario 
						WHERE USER_ID='".$usuario."' LIMIT 1";
			$resultUser   = mysqli_query($link, $sqlUser);
			$nomeUsuario  = $resultUser->fetch_assoc();
			$emailUsuario = $nomeUsuario['USER_LOGIN'];
			$nomeUsuario  = $nomeUsuario['USER_NOME'];

			$msg_usuario = "Por: ".$nomeUsuario." (".$emailUsuario.")";
		}

		echo "<h3><font color=green>Encerrado em: ".$encerramento['OCOR_DTENCERRAMENTO']." </h3> <h2>".$msg_usuario." </h2> </font>";
		$encerrado = true;
	}
														else
														{
															if($prazo != NULL && $prazo != '00/00/0000'){
																echo "<small class=\"title_left\"> Prazo da ocorrência: ".$prazo."  </small>";
															}	
														}
														if ($projeto) {
															echo "<br><label>Projeto: (".$projeto.") ".$nomeProjeto."</label>";
														}
														?>
													</h1>
													
													<?php 
													if ($impacto < 2)
														$color = 'green';
													else if ($impacto < 4)
														$color = 'orange';
													else
														$color = 'red';
													if($impacto)
														echo "<h2> <font color=\"".$color."\">Prioridade:".$impacto." </font> </h2>";
													?>
													<h2>Cliente: <?php echo $cliente;?></h2>
													<h2>Solicitante: <?php echo $solicitante?></h2>
													<h2>Contato: <?php echo $contato?></h2>
												</div>
												<!-- /.col -->
											</div>
											<!-- info row -->
											<div class="row invoice-info">

												<!-- /.col -->
												<div class="col-sm-5 invoice-col">
													<strong>Descrição completa</strong>
													<p>
														<?php echo $descricao;?>
													</p>
												</div>
												<br>
												<div class="clearfix"></div>

												<?php 
												if (($_SESSION['userId'] == '49' || 
												    $_SESSION['userId'] == '50' || 
													$_SESSION['userNivel'] == 'ADMINISTRADOR' ||
													$_SESSION['userNivel'] != 'CONSULTOR' ) && !$encerrado)
												{
													echo "<button type=\"button\" class=\"btn btn-danger\" data-toggle=\"modal\" onclick=\"popularEncerrar('".$_GET['idocorr']."')\" data-target=\".bs-example-modal-lg\">Encerrar chamado</button>";

												}
												?>
												<div class="cleafix"></div>
												<br/>
		<?php
		$value = '';
		  	if ($prazoLimite != '00/00/0000')
		  		$value = "value=".$prazoLimite."";
		?>

	<!-- prazo limite -->
	<div class="col-md-3 col-sm-3 col-xs-12">
		<form>
			<label>Prazo limite MÁXIMO (só pode ser definido uma vez, após isso um administrador deverá ser consultado para troca):
			</label>
			<div class="clearfix"></div>
			<input type="text" id="prazoLimite" class="date-picker form-control col-md-3 col-sm-3 col-xs-3" data-inputmask="'mask' : '99/99/9999'" placeholder="12/12/2016" type="text" <?php echo $value ?> >
			<div class="clearfix"></div><br>

			<?php 
				if ($prazoLimite != "00/00/0000" && $_SESSION['userNivel'] != 'ADMINISTRADOR' || $_SESSION['userNivel'] == 'USUÁRIO') {
					echo "<button type='button' id='btnSalvarPrazoLimite' class=\"btn btn-success\" disabled>Salvar Prazo Limite</button>";
				}
				else
					echo "<button type='button' id='btnSalvarPrazoLimite' class=\"btn btn-success\">Salvar Prazo Limite</button>";

			?>
		</form>
	</div>
	<script type="text/javascript">
		$('#btnSalvarPrazoLimite').on('click',function(){
			var prazo = $('#prazoLimite').val();
			var idOcorrencia = "<?php echo $_GET['idocorr'] ?>";
			$.ajax({
				type:'POST',
				url:'salvarPrazoLimite.php',
				data: {
					prazoLimite: prazo,
					idOcorrencia: idOcorrencia
				},
				success: function(result){
					alert(result);
					if(result == 'Novo limite cadastrado!') {
						
						location.reload();
					}
				},
				error: function(result){
					alert(result);
				}
			});
		});
	</script>
	<!-- /prazo limite -->
											</div>
											<!-- /.row -->
											<script type="text/javascript">
												function popularEncerrar(id)
												{
													document.getElementById('idEncerrar').value = id;
												}	
											</script>

											<!-- MODAL PARA ENCERRAMENTO -->
											<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
												<div class="modal-dialog modal-lg">
													<div class="modal-content">
														<!-- TÍTULO DO MODAL -->
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
															</button>
															<h4 class="modal-title" id="myModalLabel">Você está certo disso?</h4>
														</div>
														<!-- CONTEÚDO DO MODAL -->
														<div class="modal-body">
															<!-- MUDAR SOMENTE O ACTION DO FORM-->
															<form method=post action="../scripts/fecharOcorrencia.php">
																<label>Você está prestes a encerrar um chamado, você tem certeza disso?</label>
																<br><br>
																<button type="submit" class="btn btn-success">Sim</button>
																<button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
																<input type="text" name="idEncerrar" id="idEncerrar" hidden>
															</form>
														</div>
														<!-- FIM CONTEÚDO DO MODAL -->
													</div>
												</div>
											</div>

											<!-- FIM DO MODAL -->
											<div class="clearfix"></div>
											<!-- ASSENTAMENTOS -->
											<div class="col-md-12">
												<div class="x_title">
													<h2>Apontamentos <small>Comentários</small></h2>
													<div class="clearfix"></div>
												</div>
												<div class="x_content">
													<ul class="list-unstyled msg_list">
														<?php
														include_once "../scripts/Conexao-class.php";
														$conect = new Conexao();
														$link = $conect->getLink();

														$sql = "SELECT * FROM assentamento WHERE ASSE_ID_OCORR=".$_GET['idocorr'];
														$result = mysqli_query($link, $sql);

														if (mysqli_num_rows($result) < 1)
														{
															echo "<li>
															<a>
																<span>
																	<strong align=\"center\"> Nenhum apontamento foi encontrado para esta ocorrência.</strong>
																	</span:
																	<a/>
																</li>
																";
															}

															while ($assentamento = $result->fetch_assoc())
															{
																echo "<li style=\"text-align: justify;\">
																<a>
																	<span class=\"image\">
																	</span>
																	<span";
																	$sql = "SELECT USER_NOME FROM usuario WHERE USER_ID=".$assentamento['ASSE_USUARIO_CODIGO'];
																	$resultAux = mysqli_query($link, $sql);
																	$nomeUser = $resultAux->fetch_assoc();

																	echo "<span> <strong> Apontado por: ".$nomeUser['USER_NOME']." </span><small> em: ".$assentamento['ASSE_DTINCLUSAO']." </strong></small>
																</span>";

																echo "<span class=\"message\">
																<h4 style=\"line-height: 1.5; font-family: Verdana, Arial, sans-serif;\">".$assentamento['ASSE_DESCRICAO']."</h4>
															</span>
														</a>
													</li>";
												}
												?>
											</ul>
										</div>
									</div>
									<!-- /ASSENTAMENTOS -->
									
									<!-- arquivos -->
									<div>
										<h2>Arquivos anexados a esta ocorrência</h2>
										<ul class="arquivos">
											<?php
											$idOcorrencia = $_GET['idocorr'];
											$sql = "SELECT * FROM arquivos_chamados WHERE ac_chamado=".$idOcorrencia;
											
											$result = mysqli_query($link, $sql);

											while ($row = $result->fetch_assoc())
											{
												echo "<a href=\"baixaArquivos.php?nome=".$row['ac_conteudo']."&id=".$row['ac_id']."\" target=\"_blank\"><li>".$row['ac_nome']."</li></a>";
											}
											?>
											
											
										</ul>
									</div>
									<div class="clearfix"></div>

									<form id="demo-form" data-parsley-validate class="form-horizontal form-label-left" action="..\scripts\cadAssentamentos.php" method="post" enctype="multipart/form-data">
										<!-- INPUT ID OCORRENCIA -->
										<input type="text" id="idOcorrencia" name="idOcorrencia" value=0 hidden>
										<?php echo "<script> document.getElementById('idOcorrencia').value = ".$_GET['idocorr']."; </script>"; 
										?>
										<!-- /INPUT ID OCORRENCIA -->


										<!-- COMPONENTE DESCRIÇÃO-->			
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> Apontamento:
											</label>
											
											<div class="col-md-6 col-sm-6 col-xs-12">

												<textarea type="name" name="descricao" id="descricao" class="form-control col-md-12 col-xs-12" placeholder="Insira seu comentário aqui" style="resize: none; height: 200px" maxLength="9999"></textarea> 
											</div>

											<div class="col-md-6 col-sm-6 col-xs-12">
												<input style="width: 400px; margin: auto; padding: 10px; " type="file" name="upload[]" multiple>
											</div>
										</div>

										<?php
										$nivel = $_SESSION['userNivel'];

										if($nivel != 'USUÁRIO')
										{ 
											echo "<!-- COMPONENTE CONSULTOR DIRECIONADO (NAO MOSTRA PARA USUARIO)-->  
											<div class=\"form-group\">
												<label class=\"control-label col-md-3 col-sm-3 col-xs-12\">Direcionar para o consultor:</label>
												<div class=\"col-md-6 col-sm-6 col-xs-12\">
													<select class=\"select2_single form-control\" tabindex=\"-1\" name=\"consultor\" id=\"consultor\" value=0>
														<option value=\"0\">...</option>";

							
							if ($prazoLimiteAtrasado) {
								
								echo "<option value=\"".$ocorrenciaConsultor."\"> ".$nomeConsultorOcor." </option>";

							} else {
								include_once "../scripts/Conexao-class.php";
								$conect = new Conexao();
								$link = $conect->getLink();

								IF ($_SESSION['userNivel'] == 'ADMINISTRADOR'  or $_SESSION['userId'] == '49' or $_SESSION['userId'] == '50'  )
								{
									$sql = "SELECT CONS_ID, CONS_NOME 
											FROM consultor 
											INNER JOIN acessos
											ON ACE_USUARIO_ID=CONS_ID
											INNER JOIN usuario  ON USER_ID = CONS_ID
											AND USER_STATUS = 1
											WHERE ACE_ID_MODULOS=".$idModulo."
											AND ACE_BLOQUEADO != 1 
											AND CONS_TIPO != ''
											";
								}
								else 
								 {
									$sql = "SELECT CONS_ID, CONS_NOME 
											FROM consultor 
											INNER JOIN acessos
											ON ACE_USUARIO_ID=CONS_ID
											INNER JOIN usuario  ON USER_ID = CONS_ID
											AND USER_STATUS = 1
											WHERE ACE_ID_MODULOS=".$idModulo."
											AND ACE_BLOQUEADO != 1 
											AND CONS_TIPO != ''
											AND CONS_ID = ".$_SESSION['userId']."
											";
								}
									
								
										
								$result = mysqli_query($link,$sql);
								
								while ($row = $result->fetch_assoc())
								{	
									if ($row['CONS_NOME'] == 'ADMINISTRADOR') continue;
									$modulo = str_replace("_", " ", $row['CONS_NOME']);
									echo "<option value=\"".$row['CONS_ID']."\"> ".$modulo." </option>"
									;
									$i++;
								}
							}
														
														echo "	</select>
													</div>
												</div>

												<!-- COMPONENTE PRAZO (NAO MOSTRA PRA USUARIO)-->  
												<div class=\"form-group\">
													<label class=\"control-label col-md-3 col-sm-3 col-xs-12\" for=\"first-name\"> Prazo:
													</label>
													<div class=\"col-md-6 col-sm-6 col-xs-12\">
														";
														if ($prazo != '00/00/0000')
															echo "<input type=\"text\" name=\"prazo\" id=\"prazo\" class=\"form-control col-md-7 col-xs-12\" data-inputmask=\"'mask' : '99/99/9999'\" value=\"".$prazo."\">";
														else
															echo "<input type=\"text\" name=\"prazo\" id=\"prazo\" class=\"form-control col-md-7 col-xs-12\" data-inputmask=\"'mask' : '99/99/9999'\">";
														echo "
													</div>
												</div>";
											}
											?>
												<!-- COMPONENTE ENCAMINHAR 
												<div class="control-group" >
													<label class="control-label col-md-3 col-sm-3 col-xs-12">Encaminhar para:</label>
													<div class="col-md-6 col-sm-9 col-xs-12">
														<input id="tags_1" type="text" class="tags form-control" />
													</div>
												</div>
											-->
											<!-- LINHA -->
											<!-- BOTÃO SUBMISSÃO -->
											<?php

											if($encerramentoData == 0)
											{
												if (!$bloqueioSalvar) {
													echo	
													"<div class=\"form-group\">
													<div class=\"col-md-6 col-sm-6 col-xs-12 col-md-offset-3\">
														<div class=\"ln_solid\"></div>
														<button type=\"button\" class=\"btn btn-success\"  id=\"btnSalvar\">Salvar</button>
														<div class=\"ln-solid\"></div>
													</div>
												</div>";
											}
										}
										?>
										<!-- FIM COMPONENTES -->
										<input type="text" id="idUpdate" name="idUpdate" hidden>
										<input type="submit" id='submit' hidden>

									</form>
									<?php

									if($encerramentoData == 0)
									{
										echo "<button type=\"button\" class=\"btn btn-default\" id=\"salvaRascunho\" onclick=salvarRascunho()> Salvar Rascunho</button>";
									}

									?>
									<!-- FIM FORMULARIO -->
									
									<!-- this row will not appear when printing -->
									<div class="row no-print">
										<div class="col-xs-12">
											<button class="btn btn-default" onclick="window.print();"><i class="fa fa-print"></i> Imprimir</button>
											<button onclick="history.back()" class="btn btn-danger" ><i class="fa fa-eraser" color="white"></i><font color="white"> Voltar</font></a>
												<button id="gerarPdf" class="btn btn-primary" style="margin-right: 5px;"><i class="fa fa-download"></i> Gerar PDF</button>

												<script type="text/javascript">
													$('#gerarPdf').on('click', function(){
														window.location.replace('assePDF.php?id=<?php echo $_GET['idocorr']?>');
													});
												</script>
											</div>
										</div>
									</section>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- /page content -->
		</div>
	</div>
</div>

<!-- checa feriados e salva assentamento -->
<script type="text/javascript">
	function salvaAssentamento() {
		salvarRascunho();
		$('#submit').click();
	}

	function ehFeriado() {
		var idOcorrencia = "<?php echo $_GET['idocorr']; ?>";
		var cliente = "<?php echo $idCliente ?>";
		var data = $('#prazo').val();
		var dataVetor = data.split("/");
		var data2 = dataVetor[0]+"/"+dataVetor[1];

		$.ajax({
			type: 'POST',
			url: 'checaFeriado.php',
			data: {
				cliente: cliente,
				data: data2
			},
			success: function(result){
				if (result == '1') {
					var confirma = confirm('O prazo especificado é um feriado para este cliente, deseja continuar mesmo assim?');
					if (confirma) salvaAssentamento();
				} else salvaAssentamento();
			},
			error: function(result){
				alert(result);	
			}
		});
	}

	$('#btnSalvar').on('click', function(){
		<?php
			$nivel = $_SESSION['userNivel'];
			if($nivel == 'USUÁRIO')
				echo "salvaAssentamento();";
			else echo "ehFeriado();";
		?>
		$("#btnSalvar").prop("disabled", true);
	});
	
</script>

<!-- Verifica data-->
<script type="text/javascript">
	function validaData() {
		var prazo = $('#prazo').val();
		var prazoArranjo = prazo.split('/');
		var  ano = prazoArranjo[2];
		var  mes = prazoArranjo[1];
		var  dia = prazoArranjo[0];

		var now = new Date;
		var diaHoje = now.getDate();
		var mesHoje = now.getMonth();
		mesHoje++;

		if (diaHoje < 10) diaHoje = "0"+diaHoje;
		if (mesHoje < 10) mesHoje = "0"+mesHoje;


		if(ano < now.getFullYear()){
			alert('Datas anteriores não permitidas');
			$('#prazo').val(diaHoje+"/"+mesHoje+"/"+now.getFullYear());
		}
		if (ano == now.getFullYear())
			if (mes <= mesHoje)
				if (mes < mesHoje){
					alert('Datas anteriores não permitidas');
					$('#prazo').val(diaHoje+"/"+mesHoje+"/"+now.getFullYear());
				}
				else if (mes == mesHoje && dia < diaHoje){
					alert('Datas anteriores não permitidas');
					$('#prazo').val(diaHoje+"/"+mesHoje+"/"+now.getFullYear());
				}
			}

			$(document).ready(function(){
				var troca = 0;
				$('#prazo').on('change', function(){
					troca++;
					if(troca > 3) {
						validaData();
					}
				});
			})
		</script>

		<script>
			function limparCampos()
			{
				document.getElementById('prazo').value = "";
				document.getElementById('descricao').value = "";
				document.getElementById('tags_1').value = "";
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

	<!-- FOOTER -->
	<footer>
		<div class="pull-right">
			Compacta Dashboard desenvolvida por <a href="http://www.solucaocompacta.com.br">Solução Compacta</a>
		</div>
		<div class="clearfix"></div>
	</footer>
	<!-- /FOOTER -->
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
<!-- jQuery autocomplete -->
<script src="../vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
<!-- starrr -->
<script src="../vendors/starrr/dist/starrr.js"></script>
<!-- Select2 -->
<script src="../vendors/select2/dist/js/select2.full.min.js"></script>


<!-- ajax -->
<script type="text/javascript">
	$(document).ready(function(){
		$('#descricao').on('input', function (){
			var caract = $('#descricao').val().length;
			if (caract % 5 == 0) salvarRascunho();
		});
	});
</script>

<!-- Custom Theme Scripts -->
<script src="../build/js/custom.min.js"></script>

<!-- bootstrap-daterangepicker -->
<script>
	$(document).ready(function() {
		$('#prazo').daterangepicker({
			singleDatePicker: true,
			calender_style: "picker_4"
		}, function(start, end, label) {
			console.log(start.toISOString(), end.toISOString(), label);
		});
		$('#prazoLimite').daterangepicker({
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

<!-- jquery.inputmask -->
<script>
	$(document).ready(function() {
		$(":input").inputmask();
	});
</script>
<!-- /jquery.inputmask -->

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
			if ($("#datatable-buttons").length) {
				$("#datatable-buttons").DataTable({
					dom: "Bfrtip",
					buttons: [
					{
						extend: "copy",
						className: "btn-sm"
					},
					{
						extend: "csv",
						className: "btn-sm"
					},
					{
						extend: "excel",
						className: "btn-sm"
					},
					{
						extend: "pdfHtml5",
						className: "btn-sm"
					},
					{
						extend: "print",
						className: "btn-sm"
					},
					],
					responsive: true
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

		$('#datatable').dataTable();

		$('#datatable-keytable').DataTable({
			keys: true
		});

		$('#datatable-responsive').DataTable();

		$('#datatable-scroller').DataTable({
			ajax: "js/datatables/json/scroller-demo.json",
			deferRender: true,
			scrollY: 380,
			scrollCollapse: true,
			scroller: true
		});

		$('#datatable-fixed-header').DataTable({
			fixedHeader: true
		});

		var $datatable = $('#datatable-checkbox');

		$datatable.dataTable({
			'order': [[ 1, 'asc' ]],
			'columnDefs': [
			{ orderable: false, targets: [0] }
			]
		});
		$datatable.on('draw.dt', function() {
			$('input').iCheck({
				checkboxClass: 'icheckbox_flat-green'
			});
		});

		TableManageButtons.init();
	});
</script>
<!-- /Datatables -->   

<!-- Select2 -->
<script>
	$(document).ready(function() {
		$(".select2_single").select2({
			placeholder: "Selecione um consultor",
			allowClear: false
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

<!-- PUXAR ID DE USUÁRIO AUTOMATICAMENTE -->
<script type="text/javascript">
	$(document).ready(function() {
		<?php
		if (!isset($_SESSION)) session_start();
		$usuarioNivel = $_SESSION['userNivel'];
		$usuarioId = $ocorrenciaConsultor;
		?>

		var nivel = "<?php echo $usuarioNivel ?>";
		var id = "<?php echo $usuarioId ?>";

		if (nivel != 'USUÁRIO')
		{
			$('#consultor').val(id).change();
		}
	});
</script>

<!-- Limpar -->
<script>
	function limpar()
	{
		document.getElementById('usuario').value = "";
		document.getElementById('cliente').value = "";
		document.getElementById('prazo').value = "";
		document.getElementById('consultor').value = "";
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

<script type="text/javascript">
	function salvarRascunho() {
		var descricao = $('#descricao').val();
		var idOcorrencia = "<?php echo $_GET['idocorr']?>";

		$.ajax({
			type: 'POST',
			url: '../scripts/cadRascunho.php',
			data: 
			{
				descricao: descricao,
				idOcorrencia: idOcorrencia
			},
			success: function(result){

			},
			error: function (result) {

			}
		});
	}
</script>

</body>
</html>
<!-- puxa campos para update -->
<?php
include_once "../scripts/Conexao-class.php";
$conect = new Conexao();
$link = $conect->getLink();

$id = $_GET['idocorr'];

	//busca rascunho
$sql = "
SELECT 
RASCUNHO_TEXTO 
from 
rascunho
WHERE 
RASCUNHO_OCOR=".$id."
AND
RASCUNHO_USUARIO=".$_SESSION['userId'];

$result = mysqli_query($link, $sql);

if(!(mysqli_num_rows($result) < 1))
{
	$descricao = $result->fetch_assoc();
	$descricao = $descricao['RASCUNHO_TEXTO'];	
	$descricao = str_replace("'", "\'", $descricao);
	echo "<script> 
	$('#descricao').html(\"".$descricao."\");
</script>";
}

mysqli_free_result($result);
$conect->fechar();




exit;
?>