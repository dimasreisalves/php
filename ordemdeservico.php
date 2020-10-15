<?php
	if (!isset($_SESSION)) session_start();

	include_once "../scripts/Conexao-class.php";
	include_once "../scripts/ConversorData-class.php";
	$conversor = new ConversorData;
	$conect    = new Conexao();
	$link      = $conect->getLink();

	if (!isset($_POST['idAgenda']) || $_POST['idAgenda'] == "0") {
		echo "<script> alert('Operação inválida'); history.back();</script>";
		exit;
	}

	$sql    = "SELECT * FROM AGENDA WHERE AGEN_ID=".$_POST['idAgenda'];
	$result = mysqli_query($link, $sql);
	$dados  = $result->fetch_assoc();

	$idCliente   = $dados['AGEN_CLIENTE_ID'];
	$idConsultor = $dados['AGEN_CONSULTOR'];
	$data        = $dados['AGEN_DTAGENDA'];
	$dataSql     = $data;
	$dataOS      = $conversor->sql2Brasil($data);

	if (isset($_POST['dataInicio'])) {
		$data    = $_POST['dataInicio'];
		$dataSql = $conversor->brasil2Sql($data);
	}

	if (isset($_POST['dataFim'])) $dataFim = $_POST['dataFim'];
	else $dataFim = date('d/m/Y');
	$dataFimSql = $conversor->brasil2Sql($dataFim);

	$inicio      = $dados['AGEN_HRINICIO'];
	$fim         = $dados['AGEN_HRFIM'];
	$translado   = $dados['AGEN_TRANSLADO'];
	$descontos   = $dados['AGEN_DESCONTOS'];
	$liberado    = $dados['AGEN_LIBERADO'];
	$textoAgenda = $dados['AGEN_TEXTO'];

	/* obter nome do cliente */
	$sql = "SELECT CLI_NOME, CLI_TRANSLADO FROM CLIENTES WHERE CLI_ID=".$idCliente;
	$result           = mysqli_query($link, $sql);
	$cliente          = $result->fetch_assoc();
	$transladoCliente = $cliente['CLI_TRANSLADO'];
	$cliente          = $cliente['CLI_NOME'];

	/* obtem nome do consultor */
	$sql = "SELECT USER_NOME FROM USUARIO WHERE USER_ID=".$idConsultor;
	$result     = mysqli_query($link, $sql);
	$consultor  = $result->fetch_assoc();
	$consultor  = $consultor['USER_NOME'];
	$idAgenda   = $_POST['idAgenda'];
	$tipoAgenda = $dados['AGEN_TIPO'];

	// define translado
	if ($translado == '00:00') $translado = $transladoCliente;
	if ($tipoAgenda == 'REMOTO') {
		$translado        = '00:00';
		$transladoCliente = '00:00';
	}

	// funcao para checar atrasos
	include_once "ChecaAtraso.class.php";
	$checaAtraso = new ChecaAtraso();

	# busca OS mais atrasada
	$sql = "SELECT MIN(AGEN_DTAGENDA) AS DATA_ANTIGA FROM AGENDA
			WHERE
			AGEN_CLIENTE_ID != 99
			AND AGEN_LIBERADO LIKE '0000-00-00'
			AND AGEN_CONSULTOR = $idConsultor
			ORDER BY AGEN_DTAGENDA ASC";

	$dataMaisAntiga = mysqli_query($link, $sql)->fetch_assoc()['DATA_ANTIGA'];

	if($checaAtraso->checaAtrasoOS()) $temOSAtrasada = true;
	else $temOSAtrasada = false;

	echo "<script>";
		echo "console.log('DATA AGENDA: ".$dataSql."');";
		echo "console.log('DATA MAIS ANTIGA: ".$dataMaisAntiga."');";
		echo "console.log('DATA AGENDA ATRASADA: ".$checaAtraso->ehDataAtrasada($dataSql)."');";
		echo "console.log('DATAS DIFERENTES: ".$dataSql != $dataMaisAntiga."');";
	echo "</script>";

	$bloqueioSalvar = false;
	if (!$checaAtraso->ehDataAtrasada($dataSql) && $temOSAtrasada)
		$bloqueioSalvar = true;

	if ($checaAtraso->ehDataAtrasada($dataSql)  && $dataSql != $dataMaisAntiga)
		$bloqueioSalvar = true;

	$linkPDF = "osPDF.php?id=".$idAgenda."&idConsultor=".$idConsultor."&dataSql=".$dataSql."&dataFimSql=".$dataFimSql."&idCliente=".$idCliente;

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
		<!-- Ion.RangeSlider -->
		<link href="../vendors/normalize-css/normalize.css" rel="stylesheet">
		<link href="../vendors/ion.rangeSlider/css/ion.rangeSlider.css" rel="stylesheet">
		<link href="../vendors/ion.rangeSlider/css/ion.rangeSlider.skinFlat.css" rel="stylesheet">
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
							<div class="title_left">
								<h3>Ordem de Serviço</h3>
							</div>
						</div>

						<div class="clearfix"></div>
						<div class="row">
							<div class="col-md-12">
								<!-- Formulário da OS-->
								<!-- PAINEL DE CADASTRO -->
								<div class="x_panel">
									<!-- TÍTULO DO PAINEL-->
									<div class="x_title">
										<h2> Período da OS </h2>
										<br><br>
										<form method="POST">
											<div class="form-group">
												<label class="control-label col-md-1 col-sm-3 col-xs-12">DE:</label>
												<div class="col-md-2 col-sm-3 col-xs-12">
													<input type="text" name="dataInicio" id="dataInicio" class="form-control col-md-3 col-xs-12" data-inputmask="'mask' : '99/99/9999'">
												</div>

												<label class="control-label col-md-1 col-sm-3 col-xs-12">ATÉ:</label>
												<div class="col-md-2 col-sm-3 col-xs-12">
													<input type="text" name="dataFim" id="dataFim" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99/99/9999'">
												</div>
												<input type="text" name="idAgenda" value="<?php echo $idAgenda?>" hidden>
												<button type="submit" class="btn btn-info">Ver chamados</button>
											</div>
										</form>
										<div class="clearfix"></div>
									</div>

									<!-- CONTEÚDO DO PAINEL -->
									<div class="x_content"><br/>
										<!-- FORMULÁRIO DE CADASTRO-->
										<form class="form-horizontal form-label-left">
											<!-- componente cliente-->
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12">CLIENTE:</label>
												<div class="col-md-3 col-sm-3 col-xs-12">
													<h2 id="nomeCliente"><?php echo $cliente;?></h2>
												</div>
												<label class="control-label col-md-3 col-sm-3 col-xs-12">CONSULTOR:</label>
												<div class="col-md-3 col-sm-3 col-xs-12">
													<h2 id="nomeConsultor"><?php echo $consultor?></h2>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12">DATA:</label>
												<div class="col-md-3 col-sm-3 col-xs-12">
													<h2 id="dataOS"><?php echo $dataOS;?></h2>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12">TIPO DE AGENDA:</label>
												<div class="col-md-3 col-sm-3 col-xs-12">
													<h2 id="tipoAgenda"><?php echo $tipoAgenda;?></h2>
												</div>
											</div>
											<!-- inicio e fim -->
											<div class="form-group">
												<!-- inicio -->
												<label class="control-label col-md-3 col-sm-3 col-xs-3">INICIO</label>
												<div class="col-md-3 col-sm-3 col-xs-3">
													<input type="text" name="inicio" id="inicio" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99:99'" value="<?php echo $inicio?>">
												</div>
												<!-- fim -->
												<label class="control-label col-md-3 col-sm-3 col-xs-3">FIM</label>
												<div class="col-md-3 col-sm-3 col-xs-3">
													<input type="text" name="fim" id="fim" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99:99'" value="<?php echo $fim?>">
												</div>
											</div>

											<!--translado e outros-->
											<div class="form-group">
												<!-- translado -->
												<label class="control-label col-md-3 col-sm-3 col-xs-3">TRANSLADO</label>
												<div class="col-md-3 col-sm-3 col-xs-3">
													<input type="text" name="translado" id="translado" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99:99'" value="<?php echo $transladoCliente;?>">

													<!-- translado hidden -->
													<input type="text" id="transladoHidden" value="<?php echo $transladoCliente;?>" hidden>
												</div>
												<script type="text/javascript">
													$(document).ready(function(){
													$('#translado').attr('disabled', 'true');
													})
												</script>
												<!-- outros -->
												<label class="control-label col-md-3 col-sm-3 col-xs-3">DESCONTOS</label>
												<div class="col-md-3 col-sm-3 col-xs-3">
													<input type="text" name="outros" id="outros" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99:99'" value="<?php echo $descontos?>">
												</div>
											</div>

											<!-- COMPONENTE horas totais -->
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> TOTAL DE HORAS:
												</label>
												<div class="col-md-9 col-sm-6 col-xs-12">
													<h2 id="totalHoras"> <strong></strong> </h2>
												</div>
											</div>
											<?php
											if ($liberado == "0000-00-00")
												echo "<p>SITUAÇÃO - AGUARDANDO LIBERAÇÃO (A OS AINDA NÃO FOI SALVA)</p>";
											else if ($liberado != "0000-00-00")
												echo "<p>SITUAÇÃO - OS SALVA E LIBERADA</p>";
											?>
											<!-- LINHA -->
											<div class="ln_solid"></div>
											<div class="clearfix"> </div>
											<!-- BOTÃO SUBMISSÃO -->
											<div class="form-group">
												<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
													<?php
													if (!$bloqueioSalvar)
														echo "<button type=\"button\" class=\"btn btn-success\" id=\"btnSalvarOS\">Salvar e Liberar OS</button>";
													else
														echo "<label> Salve todas as OS em data anterior para liberar a opção de salvar nas atuais</label>";
													?>
													<button type="button" class="btn btn-danger" id="excluir">Excluir</button>
													<a href="<?php echo $linkPDF ?>" type="button" class="btn btn-primary" id="pdf" target="_blank">Visualizar PDF</a>
													<div class="ln-solid"></div>
												</div>
											</div>
											<script type="text/javascript">
												$(document).ready(function(){
													var liberado = "<?php echo $liberado?>";
													if (liberado != "0000-00-00")
														$("#btnSalvarOS").attr('disabled','true');
												})
											</script>

											<div class="ln_solid"></div>
											<div class="clearfix"> </div>

											<h3 >Chamados e assentamentos</h3>
											<font color="black">
												<?php
												include_once "../scripts/Conexao-class.php";
												include_once "../scripts/ConversorData-class.php";
												$conversor = new ConversorData;
												$conect    = new Conexao();
												$link      = $conect->getLink();

												/*Busca todas as ocorrencias do cliente entre as datas de inicio e fim definidas*/
												$sql = "SELECT DISTINCT ASSE_DESCRICAO, USER_NOME, ASSE_DTINCLUSAO, OCOR_DTENCERRAMENTO, OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_DTINCLUSAO, OCOR_ID_USUARIO FROM ASSENTAMENTO

												INNER JOIN USUARIO
												ON ASSE_USUARIO_CODIGO = USER_ID
												INNER JOIN OCORRENCIAS
												ON OCOR_ID = ASSE_ID_OCORR
												INNER JOIN CLIENTES
												ON CLI_ID = OCOR_ID_CLIENTE

												WHERE
												DATE(ASSE_DTINCLUSAO) BETWEEN '".$dataSql."' AND '".$dataFimSql."'
												AND
												USER_ID = ".$idConsultor."
												AND
												CLI_ID = ".$idCliente."
												AND ASSE_SIS != 1
												order by ocor_id";

												$result = mysqli_query($link, $sql);


												echo "<div class=\"col-md-12 col-sm-12 col-xs-12\">
												<textarea id='textoAgenda' style=\"width: 100%; height: 300px; resize: none; line-height: 1.5; text-align: justify; font-family: Verdana, Helvetica, Arial, sans-serif;\">";

													if($textoAgenda != null) {
														$textoAgenda = str_replace("<br>", "&#13;",$textoAgenda);
														echo $textoAgenda;
													}
													else
													{
														$textoDeAssentamentos = "";
														$ocorrencia = 0;
													while ($row = $result->fetch_assoc())
													{
														if ($row['ASSE_DESCRICAO'] == "") continue;
														/* nome do solicitante */
														$sql = "SELECT USER_NOME FROM USUARIO WHERE USER_ID=".$row['OCOR_ID_USUARIO'];
														$nome = mysqli_query($link, $sql);
														$nome = $nome->fetch_assoc();
														$solicitante = $nome['USER_NOME'];

														if ($row['OCOR_DTENCERRAMENTO'] == 0){
															$status = 'Aberto';
															$color = 'green';
														}
														else{
															$status = 'Fechado';
															$color = 'red';
														}

														if ($ocorrencia != $row['OCOR_ID']) {
															/* seta a nova ocorrencia*/
															$ocorrencia = $row['OCOR_ID'];

															echo "=====================================&#13;(".$row['OCOR_ID'].") CHAMADO: ".$row['OCOR_DESC_RESUMIDA']."&#13;POR: ".$solicitante." - EM: ".$row['OCOR_DTINCLUSAO']."&#13;STATUS: ".$status." &#13;=====================================&#13;&#13;";

															$textoDeAssentamentos .= "
															**************************************************************************************************************************<br/>
															Chamado: ".$row['OCOR_DESC_RESUMIDA']."<br/>por: ".$solicitante." - Em: ".$row['OCOR_DTINCLUSAO']."<br/>Status: ".$status."<br/><br/>";

														}

														$descricaoAsse = $row['ASSE_DESCRICAO'];
														$descricaoAsse = str_replace("<br/>", "&#13;",$descricaoAsse);
														$descricaoAsse = str_replace("<strong>", "",$descricaoAsse);
														$descricaoAsse = str_replace("</strong>", "",$descricaoAsse);


														echo $descricaoAsse."&#13;&#13;";

														$textoDeAssentamentos .= $row['ASSE_DESCRICAO']."<br/><br/>";
													}
													}
													echo "</textarea>
													</div>";
												?>
											</font>

											<!-- FIM COMPONENTES -->
										</form>
										<!-- FIM FORMULARIO -->
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

<!-- RATEIO DE HORAS -->
<div id="CalenderModalNew" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="myModalLabel">Rateio de horas</h4>
				<label> Distribuição das horas totais em chamados por meio de % </label>
			</div>
			<div class="modal-body">
				<div id="testmodal" style="padding: 5px 20px;">
					<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" method="post">

						<label id="aviso">
							O cadastro só será finalizado se a soma abaixo der exatamente 100%, confira no marcador a % atual.
						</label>
						<h3 id="somaAtual" style="width: 100px; margin: auto">
							0%
						</h3>

						<?php

						/*Busca todas as ocorrencias do cliente entre as datas de inicio e fim definidas*/
						$sql = "SELECT DISTINCT OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_ID_CLIENTE FROM ASSENTAMENTO
						INNER JOIN USUARIO
						ON ASSE_USUARIO_CODIGO = USER_ID
						INNER JOIN OCORRENCIAS
						ON OCOR_ID = ASSE_ID_OCORR
						INNER JOIN CLIENTES
						ON CLI_ID = OCOR_ID_CLIENTE
						WHERE
						DATE(ASSE_DTINCLUSAO)
						BETWEEN '".$dataSql."' AND '".$dataFimSql."'
						AND
						USER_ID = ".$idConsultor."
						AND
						CLI_ID = ".$idCliente."
						order by ocor_id";

							// armazenar os valores
						$result = mysqli_query($link, $sql);

							// para cada ocorrencia
						while ($row = $result->fetch_assoc()) {
							$sql = "SELECT CLI_NREDUZ FROM CLIENTES WHERE CLI_ID=".$row['OCOR_ID_CLIENTE'];
							$resultAux = mysqli_query($link, $sql);
							$nomeCliente = $resultAux->fetch_assoc();
							$nomeCliente = $nomeCliente['CLI_NREDUZ'];

							echo "
							<div class=\"form-group\" style=\"margin-bottom: 20px\">
								<label class=\"control-label col-md-4 col-sm-4 col-xs-12\">
									(".$row['OCOR_ID'].") - ".$row['OCOR_DESC_RESUMIDA']." - ".$nomeCliente."
								</label>

								<div class=\"col-md-5 col-sm-5 col-xs-12\">
									<input type=\"range\" id=\"range_".$row['OCOR_ID']."\" value=\"\" name=\"range\" />
								</div>
								<div class=\"col-md-1 col-sm-1 col-xs-1\">
									<label>%</label>
								</div>
								<div class=\"col-md-3 col-sm-3 col-xs-3\">
									<label id=\"".$row['OCOR_ID']."_hrequivalente\">00h00min</label>
								</div>
							</div>
							";
						}
						?>


						<!-- botoes -->
						<div class="form-group">
							<button class="btn btn-success" type="button" id="btnFinalizar">Finalizar e liberar OS</button>
						</div>

					</form>
				</div>
			</div>
		</div>
	</div>
</div>


<input type="text" id="valoresSlider" hidden>
<input type="text" id="somaRateio" value=0 hidden>

<script type="text/javascript">
	function salvarRateio(metadata, agenda) {
		$.ajax({
			type: "POST",
			url: "rateio.php",
			data: {
				valores: metadata,
				agenda: agenda
			},
			success: function(result) {
				if (result == "1") {
					alert('Ordem de serviço salva! Redirecionando para agenda...');
					window.location.replace('agenda.php');
				} else {
					alert("ERRO AO SALVAR RATEIO: " + result);
				}
			},
			error: function(result) {
				alert(result);
			}
		});
	}

	function salvarOS () {
		var textoOs   = $('#textoAgenda').val();
		var agenda    = "<?php echo $idAgenda;?>";
		var translado = $('#transladoHidden').val();
		var outros    = $('#outros').val();
		var liberado  = $('#liberado').val();
		var inicio    = $('#inicio').val();
		var fim       = $('#fim').val();
		var metadata  = $('#valoresSlider').val();
		var hrTotais  = $('#totalHoras').text();
		hrTotais      = hrTotais.replace("h",":");
		hrTotais      = hrTotais.replace("min","");

		if (translado == "" || outros == "" || inicio == "" || fim == "")
		{
			alert('Preencha os valores de translado e outros corretamente antes de prosseguir');
		}else{
				//inicioajax
				$.ajax({
					type: 'POST',
					url: '../scripts/cadOrdemServico.php',
					data:
					{
						idAgenda: agenda,
						translado: translado,
						outros: outros,
						hrTotais: hrTotais,
						liberado: liberado,
						inicio: inicio,
						fim: fim,
						texto: textoOs,
						linkPDF: "<?php echo $linkPDF?>",
						acao: 'salvar'
					},
					success: function(result){
						if (result == 1){
							salvarRateio(metadata, agenda);
						}
						else {
							alert(result);
						}
					},
					error: function(result){
						alert('Error on Ajax');
					}
				});
			//fimajax
		}
	}

	$('#btnFinalizar').on('click', function(){
		var soma = $('#somaRateio').val();
		if (soma > 100) {
			alert('A soma totaliza mais que 100%!');
			return;
		}
		if (soma < 100) {
			alert('A soma totaliza menos que 100%');
			return;
		}
		salvarOS();
	});
</script>


<!-- /INSERÇÃO NA AGENDA -->

<div id="fc_create" data-toggle="modal" data-target="#CalenderModalNew"></div>

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
<!-- PDF -->
<script src="jspdf/dist/jspdf.min.js"></script>
<!-- Ion.RangeSlider -->
<script src="../vendors/ion.rangeSlider/js/ion.rangeSlider.min.js"></script>

<!-- Deletar a OS-->
<script type="text/javascript">
	$(document).ready(function(){
		$('#excluir').on('click', function(){
			var agenda = "<?php echo $idAgenda;?>";
				//inicioajax
				$.ajax({
					type: 'POST',
					url: '../scripts/cadOrdemServico.php',
					data:
					{
						idAgenda: agenda,
						acao: 'deletar'
					},
					success: function(result){
						if (result == 1)
							window.location.replace('agenda.php');
						else
							alert('Erro ao deletar OS!');
					},
					error: function(result){
						alert('Critical Error on Ajax');
					}
				});
			//fimajax
		});
	});
</script>

<!-- Salvar a 'OS' -->
<script type="text/javascript">
	$(document).ready(function(){
		$('#dataInicio').on('change', function(){
			$('#topoAssentamentos').text("Chamados e assentamentos entre "+$('#dataInicio').val()+" e "+$('#dataFim').val());
		});

		$('#dataFim').on('change', function(){
			$('#topoAssentamentos').text("Chamados e assentamentos entre "+$('#dataInicio').val()+" e "+$('#dataFim').val());
		});

		$('#btnSalvarOS').on('click', function(){
			$('#fc_create').click();
		})
	})
</script>
<!-- Calculo de horas totais-->
<script type="text/javascript">
	function calculaHoras (inicio, fim, translado, desconto)
	{
		//horas
		var hrInicio    = inicio.substring(0, inicio.indexOf(":"));
		var hrFim       = fim.substring(0, fim.indexOf(":"));
		var hrTranslado = translado.substring(0, translado.indexOf(":"));
		var hrDesconto  = desconto.substring(0, desconto.indexOf(":"));
		var hrTotal     = parseInt(hrFim)       - parseInt(hrInicio)
		+ parseInt(hrTranslado) - parseInt(hrDesconto);
		var horas2Minutos = hrTotal * 60;

		//minutos
		var minInicio    = inicio.substring(inicio.indexOf(":")+1, inicio.length);
		var minFim       = fim.substring(fim.indexOf(":")+1, fim.length);
		var minTranslado = translado.substring(translado.indexOf(":")+1, translado.length);
		var minDesconto  = desconto.substring(desconto.indexOf(":")+1, desconto.length);
		var minTotal     = parseInt(minFim)       - parseInt(minInicio)
		+ parseInt(minTranslado) - parseInt(minDesconto);

		//soma de todos os minutos
		minTotal += horas2Minutos;

		var hrAuxiliar = 0;
		while (minTotal >= 60){
			minTotal -= 60;
			hrAuxiliar++;
		}

		var horaTotal = "";
		if (minTotal == 0)
			horaTotal = hrAuxiliar+"h"+"00";
		else if(minTotal < 10)
			horaTotal = hrAuxiliar+"h0"+minTotal;
		else
			horaTotal = hrAuxiliar+"h"+minTotal;

		horaTotal= horaTotal.substring(0, horaTotal.indexOf("h")+3);

		return horaTotal;
	}

	$(document).ready(function(){
		var data = "<?php echo $dataOS;?>";
		var dataFim = "<?php echo $dataFim;?>";
		$('#dataInicio').val(data);
		$('#dataFim').val(dataFim);

		var inicio    = "<?php echo $inicio ?>";
		var fim       = "<?php echo $fim ?>";
		var translado = "<?php echo $transladoCliente ?>";
		var outros    = "<?php echo $descontos ?>";

		var resultado = calculaHoras(inicio, fim, translado, outros);
		$('#totalHoras').html(resultado);

		function atualizaHorasTotais () {
			var translado = $('#translado').val();
			$('#transladoHidden').val($('#translado').val());
			if (translado == "") translado = "00:00";
			$('#translado').val(translado);

			var inicio    = $('#inicio').val();
			var fim       = $('#fim').val();
			var descontos = $('#outros').val();
			var resposta  = calculaHoras(inicio,fim, translado, descontos);
			$('#totalHoras').html(resposta);
		}

		$('#translado').on('change', function(){
			atualizaHorasTotais();
		})

		$('#outros').on('change', function(){
			atualizaHorasTotais();
		})

		$('#inicio').on('change', function(){
			atualizaHorasTotais();
		});

		$('#fim').on('change', function(){
			atualizaHorasTotais();
		});
	})
</script>

<!-- Ion.RangeSlider -->
<script>
	$(document).ready(function() {
		function atualizaValorSliders (id, valor) {
			var valores = $('#valoresSlider').val();
			// atualiza soma rateio
			var soma = parseInt($('#somaRateio').val());

			var novaSoma = parseInt(valor);

			if (valores.includes(id)){
				var sub = valores.substring(valores.indexOf(id), valores.indexOf(";", valores.indexOf(id)) + 1);
				valores = valores.replace(sub,'');

				var subValor = parseInt(sub.substring(sub.indexOf(':')+1, sub.length - 1));

				soma = soma - subValor;
			}

			valores += id+":"+valor+";";
			soma = soma + novaSoma;


			$('#valoresSlider').val(valores);
			$('#somaRateio').val(soma);
			$('#somaAtual').html(soma + "%");
		}

		function atualizaHorasReferentes (idOcorrencia, percentagem) {
			var horasTotais = $('#totalHoras').html();
			var horaVetor = horasTotais.split("h");
			var hora = horaVetor[0];
			var minuto = horaVetor[1];
			var minutosTotais = hora * 60 + parseInt(minuto);

			var minutosRef = parseInt(minutosTotais * percentagem / 100);
			var elemento = '#'+idOcorrencia+"_hrequivalente";
			var horaAux = 0;

			if (minutosRef < 60)
				$(elemento).html(minutosRef+" min");
			else {
				while (minutosRef >= 60) {
					horaAux ++;
					minutosRef -= 60;
				}

				if (minutosRef == 0)
					$(elemento).html(horaAux+"h")
				else
					$(elemento).html(horaAux+"h"+minutosRef+"min");
			}

		}

		<?php

		/*Busca todas as ocorrencias do cliente entre as datas de inicio e fim definidas*/
		$sql = "SELECT DISTINCT OCOR_ID FROM ASSENTAMENTO
		INNER JOIN USUARIO
		ON ASSE_USUARIO_CODIGO = USER_ID
		INNER JOIN OCORRENCIAS
		ON OCOR_ID = ASSE_ID_OCORR
		INNER JOIN CLIENTES
		ON CLI_ID = OCOR_ID_CLIENTE
		WHERE
		DATE(ASSE_DTINCLUSAO)
		BETWEEN '".$dataSql."' AND '".$dataFimSql."'
		AND
		USER_ID = ".$idConsultor."
		AND
		CLI_ID = ".$idCliente."
		order by ocor_id";

			// armazenar os valores
		$result = mysqli_query($link, $sql);

			// para cada ocorrencia
		while ($row = $result->fetch_assoc()) {
			echo "
			$(\"#range_".$row['OCOR_ID']."\").ionRangeSlider({
				type: \"single\",
				min: 0,
				max: 100,
				from: 0,
				to:100,
				from_fixed: false,
				onChange: function(data) {
					atualizaValorSliders('".$row['OCOR_ID']."', $(\"#range_".$row['OCOR_ID']."\").val());

					atualizaHorasReferentes('".$row['OCOR_ID']."',$(\"#range_".$row['OCOR_ID']."\").val());
				}
			});
			";
		}
		?>
	});
</script>
<!-- /Ion.RangeSlider -->

<!-- bootstrap-daterangepicker -->
<script>
	$(document).ready(function() {
		$('#dataInicio').daterangepicker({
			singleDatePicker: true,
			calender_style: "picker_4"
		}, function(start, end, label) {
			console.log(start.toISOString(), end.toISOString(), label);
		});
		$('#dataFim').daterangepicker({
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

<!-- jQuery autocomplete usuarios -->
<script>
	$(document).ready(function() {
		var countries = {<?php echo $usuarios ?>};
		var countriesArray = $.map(countries, function(value, key) {
			return {
				value: value,
				data: key
			};
		});

        // initialize autocomplete with custom appendTo
        $('#usuario').autocomplete({
        	lookup: countriesArray
        });
    });
</script>
<!-- /jQuery autocomplete -->

<!-- jQuery autocomplete clientes -->
<script>
	$(document).ready(function() {
		var countries = {<?php echo $clientes ?>};
		var countriesArray = $.map(countries, function(value, key) {
			return {
				value: value,
				data: key
			};
		});

        // initialize autocomplete with custom appendTo
        $('#cliente').autocomplete({
        	lookup: countriesArray
        });
    });
</script>
<!-- /jQuery autocomplete -->

<!-- Select2 -->
<script>
	$(document).ready(function() {
		$(".select2_single").select2({
			placeholder: "Defina a informação",
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

<!-- jQuery autocomplete consultores -->
<script>
	$(document).ready(function() {
		var countries = {<?php echo $consultores ?>};
		var countriesArray = $.map(countries, function(value, key) {
			return {
				value: value,
				data: key
			};
		});

        // initialize autocomplete with custom appendTo
        $('#consultor').autocomplete({
        	lookup: countriesArray
        });
    });
</script>
<!-- /jQuery autocomplete -->

<!-- jQuery autocomplete problemas -->
<script>
	$(document).ready(function() {
		var countries = {<?php echo $problemas ?>};
		var countriesArray = $.map(countries, function(value, key) {
			return {
				value: value,
				data: key
			};
		});

        // initialize autocomplete with custom appendTo
        $('#problema').autocomplete({
        	lookup: countriesArray
        });
    });
</script>
<!-- /jQuery autocomplete -->

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

<!-- jQuery autocomplete usuarios -->
<script>
	$(document).ready(function() {
		var countries = {<?php echo $modulos ?>};
		var countriesArray = $.map(countries, function(value, key) {
			return {
				value: value,
				data: key
			};
		});

        // initialize autocomplete with custom appendTo
        $('#modulo').autocomplete({
        	lookup: countriesArray
        });
    });
</script>
<!-- /jQuery autocomplete -->

</body>
</html>
<?php
if($liberado == 'SIM' && $_SESSION['userNivel'] != 'ADMINISTRADOR')
{
	echo "<script> $('#btnSalvarOS').attr('disabled', true);
	$('#textoAgenda').attr('readonly',true);
</script>";
}

if ($_SESSION['userNivel'] != 'ADMINISTRADOR')
	echo "<script> $('#excluir').css('display', 'none') </script>";
?>
