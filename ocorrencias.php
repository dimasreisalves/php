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

</head>

<body class="nav-md" onload="loadOff()">
	<script type="text/javascript">
		$(document).ready(function(){
			var temChamadoAtrasado = "<?php echo $temChamadoAtrasado?>";

			if (temChamadoAtrasado == 1) {
				$('#btnSalvar').attr('disabled', 'true');
				alert('Caro usuário, a opção de abrir chamado estará bloqueada até todos os chamados atrasados estarem reprogramados, cheque sua agenda de eventos na home, se estiver tentando converter um evento atrasado em chamado, reprograme seus chamados primeiro e a opção será liberada!');
			}
		})
	</script>
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
							<h3>Ocorrências</h3>
						</div>
					</div>
					<!-- ROW CADASTROS -->
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<!-- PAINEL DE CADASTRO -->
							<div class="x_panel">
								<!-- TÍTULO DO PAINEL-->  
								<div class="x_title">
									<h2> Inclusão de ocorrências <small> descreva a ocorrência abaixo </small></h2>
									<div class="clearfix"></div>
								</div>

								<!-- CONTEÚDO DO PAINEL -->
								<div class="x_content"><br/>
									<!-- FORMULÁRIO DE CADASTRO-->
									<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="..\scripts\cadOcorrencias.php" method="post">

										<!-- COMPONENTE MODULO -->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-3">Selecione o módulo:</label>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<select class="select2_single form-control" tabindex="-1" name="modulo" id="modulo" required>
													<option></option>
													<?php
													include_once "../scripts/Conexao-class.php";
													$conect = new Conexao();
													$link = $conect->getLink();
													$sql = "
													SELECT * FROM MODULOS
													INNER JOIN
													ACESSOS
													ON
													MOD_ID = ACE_ID_MODULOS
													INNER JOIN
													USUARIO
													ON
													USER_ID = ACE_USUARIO_ID
													WHERE
													MOD_BLOQUEADO != 1
													AND
													ACE_BLOQUEADO != 1
													AND
													USER_ID =".$_SESSION['userId']." ORDER BY MOD_DESC_REDUZ";
													$result = mysqli_query($link,$sql);
													$i = 1;
													while ($row = $result->fetch_assoc())
													{	
														$modulo = str_replace("_", " ", $row['MOD_DESCRICAO']);
														echo "<option value=\"".$row['MOD_ID']."\"> ".$modulo." </option>"
														;
														$i++;
													}
													?>
												</select>
											</div>

											<!-- COMPONENTE PROBLEMA-->  
											<label class="control-label col-md-3 col-sm-3 col-xs-3">Selecione o problema:</label>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<select class="select2_single form-control" tabindex="-1" name="problema" id="problema" required>
													<option></option>
													
												</select>
											</div>
										</div>


										<!-- COMPONENTE DESCRICAO RESUMIDA-->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> DESCRIÇÃO RESUMIDA</label>
											<div class="col-md-9 col-sm-6 col-xs-12">
												<input id="descricaoReduz" placeholder="Defina em poucas palavras" required="required" class="form-control" name="descricaoReduz" maxLength="140" />
											</div>
										</div>

										<!-- COMPONENTE DESCRICAO -->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> DESCRIÇÃO COMPLETA
											</label>
											<div class="col-md-9 col-sm-6 col-xs-12">
												<textarea style="resize:none; height: 250px;" id="descricao" required="required" placeholder="Dê mais detalhes" class="form-control" name="descricao" data-parsley-trigger="keyup" data-parsley-minlength="3" maxLength="15000" data-parsley-minlength-message="Descrição muito curta"
												data-parsley-validation-threshold="10"></textarea>
											</div>	
										</div>

										<!-- COMPONENTE COMPONENTE PROJETO SIM/NAO -->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-3">Projeto:</label>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<select class="select2_single form-control" tabindex="-1" id="projetoSN" required>
													<option value=0>NÃO</option>
													<option value=1>SIM</option>
												</select>
											</div>

											<!-- componente projeto -->
											<label class="control-label col-md-3 col-sm-3 col-xs-3">Selecione o projeto:</label>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<select class="select2_single form-control" tabindex="-1" name="projeto" id="projeto" required>
													<option value=0>...</option>
													
													<?php
													if($_SESSION['userNivel'] == 'USUÁRIO' || ($_SESSION['userNivel'] == 'CONSULTOR' && $_SESSION['userTipo'] == 'EXTERNO')){

														include_once "../scripts/Conexao-class.php";
														$conect = new Conexao();
														$link   = $conect->getLink();
														$sql    = "SELECT * FROM cadastro_projetos WHERE PROJ_BLOQUEADO != 1 AND PROJ_CLIENTE=".$_SESSION['userEmpresa'];
														$result = mysqli_query($link,$sql);


														while ($row = $result->fetch_assoc())
														{	
															echo "<option value=\"".$row['PROJ_ID']."\"> ".$row['PROJ_DESCRICAO']." </option>"
															;

														}
													}
													?>
												</select>
											</div>
										</div>
										
										


										<!-- COMPONENTE CLIENTE -->  
										<div class="form-group">

											<?php 
											if ($nivelUser == 'ADMINISTRADOR' || 
												($nivelUser == 'CONSULTOR' && $_SESSION['userTipo'] == 'INTERNO'))
											{

												echo "<label class=\"control-label col-md-3 col-sm-3 col-xs-3\">Selecione o cliente:</label>
												<div class=\"col-md-3 col-sm-3 col-xs-3\">
													<select class=\"select2_single form-control\" tabindex=\"-1\" name=\"cliente\" id=\"cliente\" required>
														<option></option>";

														include_once "../scripts/Conexao-class.php";
														$conect = new Conexao();
														$link = $conect->getLink();
														$sql = "SELECT * FROM clientes ORDER BY CLI_NREDUZ";
														$result = mysqli_query($link,$sql);
														$i = 1;
														while ($row = $result->fetch_assoc())
														{	
															$modulo = str_replace("_", " ", $row['CLI_NREDUZ']);
															echo "<option value=\"".$row['CLI_ID']."\"> ".$modulo." </option>"
															;
															$i++;
														}

														echo "</select>
													</div>";

												}
												?>

												<!-- COMPONENTE CONTATO  --> 
												<label class="control-label col-md-3 col-sm-3 col-xs-3" for="first-name"> Contato <span class="required">*</span>
												</label>

												<div class="col-md-3 col-sm-3 col-xs-3">
													<input type="name" name="contato" id="contato" class="form-control col-md-3 col-xs-3" required placeholder="Contato relacionado">
												</div>
											</div> 

											<input type="text" id="clienteHide" value=0 hidden>

											<!-- cliente hide -->
											<script type="text/javascript">
												$(document).ready(function(){
													$('#cliente').on('change', function(){
														$('#clienteHide').val($('#cliente').val());
														atualizaProjetos();
													});
												})
											</script>

											<!-- componente impacto-->
											<div class="form-group">
												<label class="control-label col-md-3 col-sm-3 col-xs-3">Impacto:</label>
												<div class="col-md-3 col-sm-3 col-xs-3">
													<select class="select2_single form-control" tabindex="-1" name="impacto" id="impacto" required>
														<option></option>
														<option value = "1">DÚVIDA NO PRODUTO</option>
														<option value = "2">ERRO COM PROCEDIMENTO PALEATIVO</option>
														<option value = "3">ERRO ATIVIDADE NÃO CRÍTICA</option>
														<option value = "4">ERRO SEM PROCEDIMENTO PALEATIVO</option>
													</select>
												</div>

												<label class="control-label col-md-3 col-sm-3 col-xs-3">Classificação:</label>
												<div class="col-md-3 col-sm-3 col-xs-3">
													<select class="select2_single form-control" tabindex="-1" name="classificacao" id="classificacao" required>
														<option></option>
														<option value = "1">HELPDESK</option>
														<option value = "2">IMPLANTAÇÃO</option>
														<option value = "3">DESENVOLVIMENTO</option>
													</select>
												</div>
											</div>
											
											<script type="text/javascript">
												<?php 
												  if (!isset($_SESSION)) session_start;
												  $userNivel = $_SESSION['userNivel'];
												  
												  $id = 0;
												  if (isset($_GET['id']) && $_GET['id'] != 0)
												  	$id = $_GET['id'];
												?>
												var nivel = "<?php echo $userNivel ?>";
												var id = "<?php echo $id ?>";

												if (nivel !='ADMINISTRADOR' && id != 0) {
													$('#classificacao').prop('disabled', 'disabled');
													$('#impacto').attr('disabled', 'disabled');
													$('#projeto').attr('disabled', 'disabled');
												}

											</script>

											<input type="text" id="idUpdate" name='idUpdate' value=0 hidden />
											<input type="text" id="email" name='email' value=0 hidden/>
											<!-- LINHA -->
											<div class="ln_solid"></div>
											<div class="clearfix"> </div>
											<!-- BOTÃO SUBMISSÃO -->
											<div class="form-group">
												<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
													<button type="button" id="btnSalvar" class="btn btn-success" >Salvar</button>
													<button type="button" class="btn btn-info" onclick="limpar()"> Limpar campos </button>
													<div class="ln-solid"></div>
												</div>
											</div>
											<!-- FIM COMPONENTES -->
										</form>
										<!-- FIM FORMULARIO -->
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<script type="text/javascript">
						function popularUpdate (id)
						{
							document.getElementById('idUpdate').value = id;
						}
					</script>
					<!-- NÃO REMOVER -->
					<div class="x_content">
						<!-- start form for validation -->
						<form id="demo-form" data-parsley-validate>     
						</form>
						<!-- end form for validations -->
					</div>

				</div>
				<!-- /RIGHT COL-->
				<!-- FOOTER -->
				<footer>
					<div class="pull-right">
						Compacta Dashboard desenvolvida por <a href="http://www.solucaocompacta.com.br">Solução Compacta</a>
					</div>
					<div class="clearfix"></div>
				</footer>
				<!-- /FOOTER -->
			</div>

		</div>

		<!-- FIM DA PÁGINA DE CONTEÚDO -->

	</div>
</div>
</div>

<!-- atualiza projetos dinamicamente -->
<script type="text/javascript">
	function atualizaProjetos() {
		var cliente = $('#cliente').val();

		$.ajax({
			type: 'POST',
			url: 'combosProjeto.php',
			data: {
				cliente: cliente
			},
			success: function(result){
				$('#projeto')
				.empty()
				.append(result);
			},
			error: function(){
				
			}
		});
	}
</script>

<!-- salvar ocorrencia -->
<script type="text/javascript">
	$(document).ajaxSend(function(){
		$('#btnSalvar').attr('disabled', true);
	});

	$('#btnSalvar').on('click', function() {
		var modulo         = $('#modulo').val();
		var problema       = $('#problema').val();
		var descricao      = $('#descricao').val();
		var descricaoReduz = $('#descricaoReduz').val();
		var idUpdate       = $('#idUpdate').val();
		var cliente        = $('#clienteHide').val();
		var contato        = $('#contato').val();
		var impacto        = $('#impacto').val();
		var classificacao  = $('#classificacao').val();
		var email          = $('#email').val();

		var ehProjeto    = $('#projetoSN').val();
		var projeto      = $('#projeto').val();

		if (ehProjeto != 0 && projeto == 0) {
			alert('o projeto não pode ser vazio se a ocorrência está relacionada a projeto.');
			return;
		}

		if (ehProjeto == 0 && projeto != 0) {
			alert(projeto);
			alert('o projeto deve ser vazio se a ocorrência não está relacionada a um projeto.');
			return;
		}

		if (modulo == 0 || problema == 0 || impacto == 0 || classificacao == 0){
			alert('preencha todos os campos corretamente!');
			return;
		}

		$.ajax({
			type: 'POST',
			url: '../scripts/cadOcorrencias.php',
			data: {
				modulo: modulo,
				problema: problema,
				descricaoReduz: descricaoReduz,
				descricao: descricao,
				idUpdate: idUpdate,
				cliente: cliente,
				contato: contato,
				impacto: impacto,
				classificacao: classificacao,
				projeto: projeto,
				email: email
			},
			success: function(result){
				
				if (result == 'x') {
					alert('Ocorrência atualizada.');
					location.reload();
				} else if (result == '0M') {
					alert('Erro de módulo');
					alert(result);
					return;
				} else if (result == '0P') {
					alert('Erro de campo problema');
					return;
				} else if (result == '0U') {
					alert('Erro ao atualizar');
					return;
				} else if (result == '0I') {
					alert('Erro ao 0I - inserção');
					return;
				} else if (result == '0E') {
					alert('Erro 0E - eventos');
					return;
				}

				if (result != 0 && result != 'x'){
					window.location.replace('assentamento.php?idocorr='+result);
					alert('Anote o número do seu chamado: '+result+' você será redirecionado para a página de assentamentos!');
				} else {
					alert(result);
					alert('operacao inválida!');
					return;
				}
			},
			error: function(result){
				alert(result);	
			}
		})
	})
</script>
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
					{
						extend: "csv",
						className: "btn-sm"
					},
					{
						extend: "excel",
						className: "btn-sm"
					},
					{
						extend: "print",
						className: "btn-sm"
					},
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
	function atualizaProblema() {
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
			
			}
		});
	}
	
	$(document).ready(function() {
		$('#problema')
		.attr('disabled', true);

		$('#modulo').on('change', function() {
			atualizaProblema();
		});
	});
</script>

<!-- Cliente X -->
<script type="text/javascript">
	$(document).ready(function(){
		$('#clienteX').on('change',function(){
			var cliente = $('#clienteX').val();

			$.ajax({
				type: 'POST',
				url: 'setSession.php',
				data:
				{
					cliente: cliente
				},
				success: function(result)
				{
					location.reload();
				},
				error: function(result){
					alert('Erro ao configurar cliente');
				}
			});

		});
	})
</script>
<!-- /ClienteX -->
<?php

if ($_SESSION['filtroCliente'] != "")
{
	echo "<script> $('#clienteX').val(".$_SESSION['filtroCliente'].");
	$('#cliente').val(".$_SESSION['filtroCliente'].");
	$('#clienteHide').val(".$_SESSION['filtroCliente'].");
	atualizaProjetos();
</script>";

}

?>

<!-- recupera dados dos emails -->
<script type="text/javascript">
	function recuperaEmail (idEmail) {
		/* definicao dos dados a serem recuperados
			0 assunto
			1 descricao
			2 cliente
			3 contato
		*/
		$.ajax({
			type: 'POST',
			url: 'recuperaEmail.php',
			data: {
				id: idEmail
			},
			success: function(result) {
				var vector = result.split("|");
                   	$("#descricaoReduz").val(vector[0]);
                    $("#descricao").val(vector[1]);
                    $("#cliente").val(vector[2]).change();
					$("#contato").val(vector[3]);
			},
			error: function(result) {
				alert(result);
			}
		})
	}
	<?php 
	//recupera campos do email

	if (isset($_GET['email'])) {
		$idEmail = $_GET['email'];
		echo "recuperaEmail(".$idEmail.");";
		echo "$('#email').val(".$idEmail.");";
	}

	?>
</script>



<!-- conversao de evento em chamado -->
<?php
if(isset($_GET['assunto'])){
	$cliente   = $_GET['idCliente'];
	$consultor = $_GET['idConsultor'];
	$assunto   = $_GET['assunto'];
	$descricao = $_GET['descricao'];
	$prazo     = $_GET['prazo'];
	$evento    = $_GET['idEvento'];
	$modulo    = $_GET['idModulo'];
	echo "<script>";
	echo "$('#descricao').html('".$descricao."');";
	echo "$('#descricaoReduz').val('".$assunto."');";
	echo "$('#cliente').val(".$cliente.").change();";
	echo "atualizaProjetos();";
	echo "$('#cliente').attr('disabled','true');";
	echo "$('#clienteHide').val(".$cliente.");";
	echo "$('#modulo').val(".$modulo.").change();";
	echo "atualizaProblema();";
	echo "</script>";

	if (!isset($_SESSION)) session_start();
	$_SESSION['consultorOcorrencia'] = $consultor;
	$_SESSION['prazoOcorrencia'] = $prazo;
	$_SESSION['eventoOcorrencia'] = $evento;
}
?>


</body>
</html>
<!-- puxa campos para update -->
<?php
//puxar valores para os campos
if (isset($_GET['id']) && !isset($_GET['email']))
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
atualizaProjetos();
$('#cliente').attr('disabled',true);
$('#contato').attr('disabled',true);

</script>";

echo "<script>";
$projeto = $dados['OCOR_PROJETO'];
if (!$projeto) {
	echo "$('#projetoSN').val(0);";
} else {
	echo "$('#projetoSN').val(1);";
	echo "$('#projeto').val(".$projeto.");";
}
echo "</script>";

mysqli_free_result($result);
$conect->fechar();
}

exit;
?>




