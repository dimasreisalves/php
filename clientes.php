<!-- CADASTRO DE CLIENTES -->
<?php
include_once "../scripts/Conexao-class.php";
$conect = new Conexao();
$link = $conect->getLink();
$sql = "SELECT USER_NOME,USER_ID FROM usuario";
$result = mysqli_query($link, $sql);
$data = $result->fetch_assoc();
$nomes = "A1:\"(".$data['USER_ID'].") ".$data['USER_NOME']."\",";
$i = 2;
while($data = $result->fetch_assoc())
{
	$nomes .= "A".$i.":\"(".$data['USER_ID'].") ".$data['USER_NOME']."\",";
	$i++;
}
$nomes = substr($nomes, 0 , strlen($nomes) - 1);

if(!isset($_SESSION)) session_start();
if ($_SESSION['userNivel'] != 'ADMINISTRADOR')
{
	echo "<script> window.location.replace('home.php'); </script>";
	exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="textc/html; charset=UTF-8">
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
	<!-- Select2 -->
	<link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
	<!-- Custom Theme Style -->
	<link href="../build/css/custom.min.css" rel="stylesheet">
	<!-- LOADER -->
	<link href='loader.css' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Lato:900,400' rel='stylesheet' type='text/css'>
	<!-- PNotify -->
	<link href="../vendors/pnotify/dist/pnotify.css" rel="stylesheet">
	<link href="../vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
	<link href="../vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
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

			<!-- CONTEÚDO DA PÁGINA-->
			<div class="right_col" role="main">
				<div class="">
					<!-- TÍTULO -->
					<div class="page-title">
						<div class="title_left">
							<h3>Cadastro.</h3>
						</div>
					</div>

					<!-- ROW CADASTROS -->
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<!-- PAINEL DE CADASTRO -->
							<div class="x_panel">
								<!-- TÍTULO DO PAINEL-->  
								<div class="x_title">
									<h2> Cadastro de clientes <small> insira os dados abaixo</small></h2>
									<div class="clearfix"></div>
								</div>
								<!-- CONTEÚDO DO PAINEL -->
								<div class="x_content"><br/>
									<!-- FORMULÁRIO DE CADASTRO-->
									<form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="..\scripts\cadClientes.php" method="post">
										<!-- COMPONENTE CHAVE CEP-->
										<input type="text" id="enderecoCep" hidden />
										

										<!-- COMPONENTE ESCOLHA DE CPF OU CNPJ -->
										<div class="form-group" id='divIdentificaCodigo'>
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Identificação</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<div id="gender" class="btn-group" data-toggle="buttons">
													<label id='lblCNPJ' class="btn btn-primary" onclick='ehCNPJ()'>
														&nbsp; CNPJ &nbsp;
													</label>
													<label id='lblCPF' class="btn btn-default" onclick='ehCPF()'>
														CPF
													</label>
												</div>
											</div>
										</div>
										<input id='tipoCod' value="cnpj" type='text' name='tipoCod' hidden>
										<script>
											function ehCPF()
											{
												document.getElementById('tipoCod').value = "cpf";
												document.getElementById('formCNPJ').hidden = true;
												document.getElementById('formCPF').hidden = false;
												
												document.getElementById('lblCPF').className = 'btn btn-primary';
												document.getElementById('lblCNPJ').className = 'btn btn-default';
												
											}
											function ehCNPJ()
											{
												document.getElementById('tipoCod').value = "cnpj";
												document.getElementById('formCNPJ').hidden = false;
												document.getElementById('formCPF').hidden = true;
												document.getElementById('lblCPF').className = 'btn btn-default';
												document.getElementById('lblCNPJ').className = 'btn btn-primary';
											}
										</script>

										<!-- COMPONENTE CNPJ-->  
										<div class="form-group" id='formCNPJ'>
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" id="cnpjLabel">CNPJ <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="cnpj" id="cnpj" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99.999.999/9999-99'" placeholder="99.999.999/9999-99" oninput="validaCnpj()">
											</div>
										</div>

										<script type="text/javascript">
											function validaCnpj()
											{
												var cnpj = document.getElementById('cnpj').value;
												cnpj = cnpj.replace(".","");
												cnpj = cnpj.replace("/","");
												cnpj = cnpj.replace("-","");
												cnpj = cnpj.replace(".","");
												cnpj = cnpj.replace("_","");
												var cnpjOriginal = cnpj;

												if (cnpj.length == 14)
												{
													var primeirosNumeros = cnpj.substring(0, 12);		
													/* primeiro digito */
													var primeiroCalculo = multiplicaCnpj(primeirosNumeros, 5);
													var primeiroDigito = 0;
													if ((primeiroCalculo % 11) >= 2)
														primeiroDigito = 11 - (primeiroCalculo % 11);
													primeirosNumeros += primeiroDigito;

													/* Segundo digito */
													var segundoCalculo = multiplicaCnpj(primeirosNumeros, 6);
													var segundoDigito = 0;
													if ((segundoCalculo % 11) >= 2)
														segundoDigito = 11 - (segundoCalculo % 11);
													primeirosNumeros += segundoDigito;

													if (cnpjOriginal != primeirosNumeros)
													{
														alert('CNPJ inválido');
														document.getElementById('cnpj').value = '';
														document.getElementById('cpf').value = '';
													}
												}
											}

											function multiplicaCnpj (cnpj, posicao)
											{
												var calculo = 0;
												for (var i = 0; i < cnpj.length; i++)
												{
													calculo = calculo + (posicao * cnpj.charAt(i));
													posicao--;
													if (posicao < 2) posicao = 9;
												}
												return calculo;
											}

											function validaCpf ()
											{
												cpf = document.getElementById('cpf').value;
												cpf = cpf.replace(".","");
												cpf = cpf.replace("-","");
												cpf = cpf.replace("_","");
												cpf = cpf.replace(".","");

												if (cpf.length > 10)
												{
													var digitos = cpf.substring(0, 9);
													var novoCpf = calculaPosicaoDigitos(digitos, 10, 0);
													novoCpf = calculaPosicaoDigitos(novoCpf, 11, 0);
													if (novoCpf != cpf)
													{
														alert('CPF inválido');
														document.getElementById('cpf').value = '';
														document.getElementById('cnpj').value = '';
													}
												}
												
											}	

											function calculaPosicaoDigitos (digitos, posicoes, soma)
											{
												for (var i = 0; i < digitos.length; i++)
												{
													soma += digitos.charAt(i) * posicoes;
													posicoes--;
												}
												soma = soma % 11;
												if (soma < 2)
													soma = 0;
												else
													soma = 11 - soma;
												soma = soma.toString();
												var cpf = digitos+soma;

												return cpf;
											}
										</script>

										<!-- COMPONENTE CPF-->  
										<div class="form-group" id='formCPF' hidden>
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name" id="cpfLabel">CPF <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="cpf" id="cpf" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '999.999.999-99'" placeholder="123.456.789-10" oninput="validaCpf()">
											</div>
										</div>

										<!-- COMPONENTE INSCRIÇÃO-->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> INSCRIÇÃO
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="inscricao" id="inscricao" class="form-control col-md-7 col-xs-12" placeholder="Inscrição estadual" maxLength=18>
											</div>
										</div>

										

										<!-- COMPONENTE NOME-->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-3" for="first-name"> NOME <span class="required">*</span>
											</label>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<input type="text" name="nome" id="nome" class="form-control col-md-7 col-xs-12" placeholder="Nome do cliente" required maxLength="120">
											</div>
										</div>
										<!-- COMPONENTE NOME ABREV-->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-3" for="first-name">NOME ABREV <span class="required">*</span>
											</label>
											<div class="col-md-3 col-sm-3 col-xs-12">
												<input type="text" name="nomeAbrev" id="nomeAbrev" class="form-control col-md-7 col-xs-12" placeholder="Nome abreviado" required maxLength="60">
											</div>
										</div>     
										<!-- COMPONENTE CEP-->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> CEP <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="cep" id="cep" class="form-control col-md-7 col-xs-12" onblur="pesquisacep(this.value);" data-inputmask="'mask' : '99999-999'" placeholder="07780-000" required>
												<br>
												<!-- BOTAO PARA CHAMAR MODAL-->
												<button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bs-example2-modal-lg">Ver no mapa...</button>
											</div>
										</div> 


										<!-- MAPA -->
										<div class="modal fade bs-example2-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog modal-lg">
												<div class="modal-content">
													<!-- TÍTULO DO MODAL -->
													<div class="modal-header">
														<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
														</button>
														<h4 class="modal-title" id="myModalLabel">Veja seu cliente no mapa:</h4>
													</div>
													<!-- CONTEÚDO DO MODAL -->
													<div class="modal-body">
														<iframe class="calendar" src="https://www.google.com/maps/embed/v1/place?q=Carapicuiba+Cobal&key=AIzaSyD7UuaO557GPJOFsdw-g59IRGPleJw6N5w" width="100%" height="400px" id='mapa' frameborder="0"  style="border:0">
														</iframe>			
													</div>
													<!-- FOOTER DO MODAL-->
													<div class="modal-footer">
														<button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
													</div>
												</div>
											</div>
										</div>

										<!-- COMPONENTE ENDERECO-->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> ENDEREÇO <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="endereco" id="endereco" class="form-control col-md-7 col-xs-12" placeholder="Endereço" required maxLength="200">
											</div>
										</div>
										<!-- COMPONENTE NÚMERO -->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> Nº <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="numero" id="numero" class="form-control col-md-7 col-xs-12" placeholder="Número" required maxLength="10">
											</div>
										</div>
										<!-- COMPONENTE BAIRRO-->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> BAIRRO <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="bairro" id="bairro" class="form-control col-md-7 col-xs-12" placeholder="Bairro do cliente" required maxLength="70">
											</div>
										</div>     
										<!-- COMPONENTE MUNICÍPIO-->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> CIDADE <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="municipio" id="municipio" class="form-control col-md-7 col-xs-12" placeholder="Município do cliente" required maxLength="90">
											</div>
										</div>     
										<!-- COMPONENTE ESTADO-->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> ESTADO<span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="estado" id="estado" class="form-control col-md-7 col-xs-12" placeholder="UF do cliente" maxLength=2 required>
												<input type="text" name="estadoHidden" id="estadoHidden" hidden>
											</div>
										</div> 
										<!-- COMPONENTE COMPLEMENTO -->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> COMPLEMENTO<span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="complemento" id="complemento" class="form-control col-md-7 col-xs-12" placeholder="Complemento do cliente" maxLength="120">
											</div>
										</div> 			

										<!-- COMPONENTE TRANSLADO -->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> TRANSLADO
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="text" name="translado" id="translado" class="form-control col-md-7 col-xs-12" placeholder="00:00" maxLength=6 data-inputmask="'mask' : '99:99'">
											</div>
										</div> 

										<!-- COMPONENTE CONTATO-->					
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">CONTATO <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="name" name="contato" id="contato" class="form-control col-md-7 col-xs-12" placeholder="Contato do cliente" required maxLength="20">
											</div>
										</div>                         
										<!-- COMPONENTE TELEFONE-->
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">TELEFONE <span class="required">*</span>
											</label>
											<div class="col-md-6 col-sm-6 col-xs-12">
												<input type="name" name="telefone" id="telefone" required="required" data-inputmask="'mask' : '(99) 9999-9999'" placeholder="(99) 9999-9999" class="form-control col-md-7 col-xs-12" required>
											</div>
										</div>

										<!-- COMPONENTE responsavel -->  
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-3">Selecione o responsável:</label>
											<div class="col-md-3 col-sm-3 col-xs-3">
												<select class="select2_single form-control" tabindex="-1" name="idresponsavel" id="idresponsavel" required>

													<option></option>
													<?php
													include_once "../scripts/Conexao-class.php";
													$conect = new Conexao();
													$link = $conect->getLink();
													$sql = "SELECT * FROM usuario WHERE USER_BLOQUEADO != 1";
													$result = mysqli_query($link,$sql);
													$i = 1;
													while ($row = $result->fetch_assoc())
													{	
														$modulo = str_replace("_", " ", $row['USER_NOME']);
														echo "<option value=\"".$row['USER_ID']."\"> ".$modulo." </option>"
														;
														$i++;
													}
													?>
												</select>
											</div>
										</div>

										<!-- COMPONENTE BLOQUEADO -->
										<div class="form-group">
											<label class="control-label col-md-3 col-sm-3 col-xs-12">Cliente bloqueado?</label>
											<div class="col-md-9 col-sm-9 col-xs-12">
												<div class="">
													<label>
														Não <input type="checkbox" class="js-switch" name="bloqueado" id="ativo" unchecked /> Sim
													</label>
												</div>
											</div>
										</div>

										<!-- LINHA -->
										<div class="ln_solid"></div>
										<!-- BOTÃO SUBMISSÃO -->
										<div class="form-group">
											<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
												<button type="submit" class="btn btn-success" >Salvar</button>
												<button type="button" class="btn btn-info"  onclick="limpar()">Limpar campos</button>
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


					<!-- TABELA DE CONSULTAS DE CLIENTES -->
					<div class="x_panel">
						<div class="x_content">
							<!-- consulta usuarios cadastrados no banco-->
							<?php
							include_once "../scripts/Conexao-class.php";
							$conect = new Conexao();
							$db= $conect->getLink();
							$result = $db->query('SELECT * FROM `clientes`');

							echo "<table id=\"datatable-responsive\" class=\"table table-striped table-bordered dt-responsive nowrap\" cellspacing=\"0\" width=\"100%\">
							<thead>
								<tr>
									<th>ID CLIENTE</th>
									<th>NOME</th>
									<th>CNPJ</th>
									<th>ESTADO</th>
									<th>MUNICIPIO</th>
									<th>ENDERECO</th>
									<th>OPÇÕES</th>
								</tr>
							</thead>
							<tbody>";
								if($result){
									while ($row = $result->fetch_assoc()){
										if ($row['CLI_BLOQUEADO'] == 1)
											$bloqueado = 'SIM';
										else
											$bloqueado = 'NÃO';

										if($bloqueado == 'SIM')
											$linha = "<tr style=\"color: #fd5e5e; background: #fdc3c3;\">";
										else
											$linha = "<tr>";

										echo $linha;
										echo "<td>" . $row['CLI_ID'] . "</td>";
										echo "<td>" . $row['CLI_NOME'] . "</td>";
										echo "<td>" . $row['CLI_CNPJ'] . "</td>";
										echo "<td>" . $row['CLI_EST'] . "</td>";
										echo "<td>" . $row['CLI_MUN'] . "</td>";
										echo "<td>" . $row['CLI_END'] . "</td>";
										echo "<td>
										<form method=post action =\"?id=".$row['CLI_ID']."\">
											<button id=".$row['CLI_ID']." type=\"submit\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i> Editar </button>
											<button type=\"button\" onclick=\"popularDeletar(".$row['CLI_ID'].")\" class=\"btn btn-danger btn-xs\" data-toggle=\"modal\" data-target=\".bs-example-modal-lg\">Deletar</button>
										</form>
									</td>";
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
				<!-- FIM TABELA -->
				<!-- NÃO REMOVER -->
				<div class="x_content">
					<!-- start form for validation -->
					<form id="demo-form" data-parsley-validate>     
					</form>
					<!-- end form for validations -->
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

	<!-- FIM DA PÁGINA DE CONTEÚDO -->
	<!-- MODAL PARA DELEÇÃO -->
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
					<form method=post action="../scripts/genericDel.php">
						<label>Você está prestes a deletar um registro, tem plena certeza do que está fazendo?</label>
						<button type="submit" class="btn btn-success">Sim</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
						<input type="text" name="idDeletar" id="idDeletar" hidden>
						<input type="text" name="idEntidade" id="idEntidade" value="clientes" hidden>
					</form>
				</div>
				<!-- FIM CONTEÚDO DO MODAL -->
			</div>
		</div>
	</div>

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
<!-- bootstrap-progressbar -->
<script src="../vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
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
<!-- Switchery -->
<script src="../vendors/switchery/dist/switchery.min.js"></script>   
<!-- Parsley -->
<script src="../vendors/parsleyjs/dist/parsley.js"></script>
<!-- Autosize -->
<script src="../vendors/autosize/dist/autosize.min.js"></script>
<!-- jquery.inputmask -->
<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
<!-- Dropzone.js -->
<script src="../vendors/dropzone/dist/min/dropzone.min.js"></script>
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
<!-- Select2 -->
<script src="../vendors/select2/dist/js/select2.full.min.js"></script>
<!-- PNotify -->
<script src="../vendors/pnotify/dist/pnotify.js"></script>
<script src="../vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="../vendors/pnotify/dist/pnotify.nonblock.js"></script>

<!-- Custom Theme Scripts -->
<script src="../build/js/custom.min.js"></script>

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

<!-- PNotify -->
<script>
	$(document).ready(function() {
		$('#cnpj').on('input',function(){
			var cnpj = $('#cnpj').val();
			var cnpj2 = cnpj.replace(".","");
			cnpj2 = cnpj2.replace("-","");
			cnpj2 = cnpj2.replace("/","");
			cnpj2 = cnpj2.replace("_","");
			cnpj2 = cnpj2.replace(".","");
			if (cnpj2.length == 14)
			{
				$.ajax({
					type: 'POST',
					url: 'verificaMatricula.php',
					data: {
						codigo: cnpj
					},
					success: function(result){
						if (result != 0)
						{
							new PNotify({
								title: "Verifique o CNPJ",
								type: "error",
								text: "Detectamos que o CNPJ já está cadastrado no nosso sistema.",
								nonblock: {
									nonblock: false
								},
								addclass: 'success',
								styling: 'bootstrap3',
								hide: false,
								before_close: function(PNotify) {
									PNotify.update({
										title: PNotify.options.title + " - Enjoy your Stay",
										before_close: null
									});

									PNotify.queueRemove();

									return false;
								}
							});
							var cnpjAtual = $('#cnpj').val();
							cnpjAtual = cnpjAtual.substring(0, cnpjAtual.length-1);
							$('#cnpj').val(cnpjAtual);
						}

					}
				});
			}
		});

		$('#cpf').on('input',function(){
			var cpf = $('#cpf').val();
			var cpf2 = cpf.replace(".","");
			cpf2 = cpf2.replace("-","");
			cpf2 = cpf2.replace("_","");
			cpf2 = cpf2.replace(".","");

			if (cpf2.length == 11)
			{
				$.ajax({
					type: 'POST',
					url: 'verificaMatricula.php',
					data: {
						codigo: cpf
					},
					success: function(result){
						if (result != '0')
						{
							new PNotify({
								title: "Verifique o CPF",
								type: "error",
								text: "Detectamos que o CPF já está cadastrado no nosso sistema.",
								nonblock: {
									nonblock: false
								},
								addclass: 'success',
								styling: 'bootstrap3',
								hide: false,
								before_close: function(PNotify) {
									PNotify.update({
										title: PNotify.options.title + " - Enjoy your Stay",
										before_close: null
									});

									PNotify.queueRemove();

									return false;
								}
							});
							var cpfAtual = $('#cpf').val();
							cpfAtual = cpfAtual.substring(0, cpfAtual.length-1);
							$('#cpf').val(cpfAtual);
						}

					}
				});
			}
		});



	});
</script>
<!-- /PNotify -->

<!-- Seta o novo endereco do mapa -->
<script>
	function buscaLocal()
	{
		var rua = document.getElementById('enderecoCep').value;
		rua = rua.replace(" ","+");
		document.getElementById('mapa').src = "https://www.google.com/maps/embed/v1/place?q="+rua+"&key=AIzaSyD7UuaO557GPJOFsdw-g59IRGPleJw6N5w";
	}
</script>
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
<!-- Busca dados por CEP -->
<script type="text/javascript" >

	function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('endereco').value=("");
            document.getElementById('bairro').value=("");
            document.getElementById('municipio').value=("");
            document.getElementById('estado').value=("");
            document.getElementById('complemento').value=("");
        }


        function meu_callback(conteudo) {
        	if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            var estado = document.getElementById('estadoHidden').value;
            if(conteudo.uf != estado && estado != "")
            {
            	alert('Um estado por CNPJ/CPF');
            }
            else
            {
            	document.getElementById('endereco').value=(conteudo.logradouro);
            	document.getElementById('bairro').value=(conteudo.bairro);
            	document.getElementById('municipio').value=(conteudo.localidade);
            	document.getElementById('estado').value=(conteudo.uf);
            	document.getElementById('complemento').value=(conteudo.complemento);
            	document.getElementById('enderecoCep').value=(conteudo.logradouro);
            	document.getElementById('enderecoCep').value +=	" "+(conteudo.bairro);
            	buscaLocal();
            }
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }

    function pesquisacep(valor) {

        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                document.getElementById('estado').value="...";
                document.getElementById('bairro').value="...";
                document.getElementById('municipio').value="...";
                document.getElementById('estado').value="...";
                document.getElementById('complemento').value="...";
                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = '//viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    };

</script>

<!-- jQuery autocomplete -->
<script>
	$(document).ready(function() {
		var countries = {<?php echo $nomes ?>};
		var countriesArray = $.map(countries, function(value, key) {
			return {
				value: value,
				data: key
			};
		});

        // initialize autocomplete with custom appendTo
        $('#idresponsavel').autocomplete({
        	lookup: countriesArray
        });
    });
</script>
<!-- /jQuery autocomplete -->

<!-- Limpar -->
<script>
	function limpar()
	{
		document.getElementById('cnpj').value = "";
		document.getElementById('inscricao').value = "";
		document.getElementById('nome').value = "";
		document.getElementById('nomeAbrev').value = "";
		document.getElementById('cep').value = "";
		document.getElementById('endereco').value = "";
		document.getElementById('bairro').value = "";
		document.getElementById('municipio').value = "";
		document.getElementById('estado').value = "";
		document.getElementById('estadoHidden').value = "";
		document.getElementById('complemento').value = "";
		document.getElementById('contato').value = "";
		document.getElementById('telefone').value = "";
		document.getElementById('idresponsavel').value = "";
		document.getElementById('numero').value = "";
		document.getElementById('cnpj').readOnly = false;
		document.getElementById('cpf').readOnly = false;
	}
</script>
<!-- /Limpar -->
<!-- POPULAR DELETAR -->
<script>
	function popularDeletar(id)
	{
		document.getElementById('idDeletar').value = id;
	}
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
  echo "<script> $('#clienteX').val(".$_SESSION['filtroCliente'].") </script>";
}

?>

</body>
</html>

<!-- puxa campos para update -->
<?php
if(@$_GET['id'] > 0 || $_SESSION['filtroCliente'] != 0)
{
	
	if (isset($_GET['id']))
		$id = $_GET['id'];
	else
		$id = $_SESSION['filtroCliente'];
	include_once "../scripts/Conexao-class.php";
	$conect = new Conexao();
	$db = $conect->getLink();
	$sql = "SELECT * FROM clientes WHERE CLI_ID=".$id;
	$result = $db->query($sql);
	$dados = $result->fetch_assoc();
	
	if ($dados['CLI_TIPOCOD'] == 'cnpj'){
		echo "<script> document.getElementById('cnpj').value             = '".$dados['CLI_CNPJ']."'; </script>";
		echo "<script> 
		document.getElementById('tipoCod').value='cnpj';
		document.getElementById('formCNPJ').hidden = false;
		document.getElementById('formCPF').hidden = true;
		document.getElementById('cnpj').readOnly = true;
		document.getElementById('cpf').readOnly = false;
	</script>";		
}
else if ($dados['CLI_TIPOCOD'] == 'cpf'){
	echo "<script> document.getElementById('cpf').value              = '".$dados['CLI_CNPJ']."'; </script>";
	echo "<script> 
	document.getElementById('tipoCod').value='cpf';
	document.getElementById('formCNPJ').hidden = true;
	document.getElementById('formCPF').hidden = false;
	document.getElementById('cpf').readOnly = true;
	document.getElementById('cnpj').readOnly = false;
</script>";
}

$nome = $dados['CLI_NOME'];
$nome = str_replace("'", "\'", $nome);
$nomeReduz = $dados['CLI_NREDUZ'];
$nomeReduz = str_replace("'", "\'", $nomeReduz);
$endereco = $dados['CLI_END'];
$endereco = str_replace("'", "\'", $endereco);
$numero = $dados['CLI_NUM'];
$numero = str_replace("'", "\'", $numero);
$bairro =  $dados['CLI_BAIRRO'];
$bairro = str_replace("'", "\'", $bairro);
$municipio= $dados['CLI_MUN']; 
$municipio = str_replace("'", "\'", $municipio);
$estado = $dados['CLI_EST'];
$estado = str_replace("'", "\'", $estado);
$complemento = $dados['CLI_COMPLEMENTO'];
$complemento = str_replace("'", "\'", $complemento);
$contato = $dados['CLI_CONTATO'];
$contato = str_replace("'", "\'", $contato);
$telefone = $dados['CLI_TEL'];
$inscricao = $dados['CLI_INSCRI'];
$inscricao = str_replace("'", "\'", $inscricao);
$translado = $dados['CLI_TRANSLADO'];


echo "<script>
document.getElementById('divIdentificaCodigo').style.display = 'none';
</script>";
echo "<script> document.getElementById('inscricao').value        = '".$inscricao."'; </script>";
echo "<script> document.getElementById('nome').value             = '".$nome."'; </script>";
echo "<script> document.getElementById('nomeAbrev').value        = '".$nomeReduz."'; </script>";
echo "<script> document.getElementById('cep').value              = '".$dados['CLI_CEP']."'; </script>";
echo "<script> document.getElementById('endereco').value         = '".$endereco."'; </script>";
echo "<script> document.getElementById('numero').value           = '".$numero."'; </script>";
echo "<script> document.getElementById('bairro').value           = '".$bairro."'; </script>";
echo "<script> document.getElementById('municipio').value        = '".$municipio."'; </script>";
echo "<script> document.getElementById('estado').value           = '".$estado."'; </script>";
echo "<script> document.getElementById('estadoHidden').value     = '".$estado."'; </script>";
echo "<script> document.getElementById('complemento').value      = '".$complemento."'; </script>";
echo "<script> document.getElementById('contato').value          = '".$contato."'; </script>";
echo "<script> document.getElementById('telefone').value         = '".$dados['CLI_TEL']."'; </script>";
echo "<script> document.getElementById('idresponsavel').value    = '".$dados['CLI_ID_RESPONSAVEL']."'; </script>";
echo "<script> document.getElementById('translado').value    = '".$translado."'; </script>";

if ($dados['CLI_BLOQUEADO'] == 1) echo "<script> document.getElementById('ativo').checked = true; </script>";
else echo "<script> document.getElementById('ativo').checked = false; </script>";


}
exit;
?>

