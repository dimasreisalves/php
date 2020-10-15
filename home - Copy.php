<?php
if (!isset($_SESSION)) session_start();
if ($_SESSION['userNivel'] == 'USUÁRIO')
{
  echo "<script> window.location.replace('ocorrencias.php');</script>";
}

if ($_SESSION['userNivel'] == 'CONSULTOR' && $_SESSION['userTipo'] == 'INTERNO')
  $_SESSION['filtroConsultor'] = $_SESSION['userId'];

//testes nova funcao
include_once "ChecaAtraso.class.php";
$checaAtraso = new ChecaAtraso();

if($checaAtraso->checaAtrasoOS()) $temOSAtrasada = true;
else $temOSAtrasada = false;

if ($temOSAtrasada) $mensagem = "<small style=\"color: red\">Caro usuário, você não poderá salvar OS's atuais enquanto houverem OS atrasadas pendentes!</small>";
else $mensagem = "<small></small>";

// checa feriados
include_once '../scripts/Conexao-class.php';
$conexao = new Conexao();
$link = $conexao->getLink();
$cliente = $_SESSION['filtroCliente'];

$sqlFeriados = "SELECT feriado_data FROM feriados ";

if ($cliente == 0) $sqlFeriados .= " WHERE feriado_cliente > -1";
else $sqlFeriados .= " WHERE feriado_cliente =".$cliente." OR feriado_cliente = 0";

$sqlFeriados .= " order by feriado_data";

$result = mysqli_query($link, $sqlFeriados);
$listaFeriados = "";

while ($row = $result->fetch_assoc())
  $listaFeriados .= $row['feriado_data'].";";

// echo $listaFeriados;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Solução Compacta </title>

  <!-- Bootstrap -->
  <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- FullCalendar -->
  <link href="../vendors/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
  <link href="../vendors/fullcalendar/dist/fullcalendar.print.css" rel="stylesheet" media="print">
  <!-- Select2 -->
  <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <!-- PNotify -->
  <link href="../vendors/pnotify/dist/pnotify.css" rel="stylesheet">
  <link href="../vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
  <link href="../vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
  <!-- Custom styling plus plugins -->
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
    .legenda {
      margin-bottom: 9px;
      font-size: 105%;
    }

    .legenda span {
      margin-left: 3%;
      display: inline-block;
    }

    span#confirmado { color: #3bb300; }
    span#reservado  { color: gray; }
    span#remoto     { color: #333; }
    span#liberado   { color: #ffd600; }
    span#exportado  { color: #ff291f; }
    span#particular { color: #66ffff; }

  </style>
</head>

<body class="nav-md" onload="loadOff()">


  <div class="container body">
    <div class="main_container">
      <?php
      include_once "MontaMenu.php";
      $menu = new MontaMenu();

      ?>


      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left" style="width: 100%">
              <h3>Agenda <?php echo $mensagem?></h3>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="row">
            <div class="col-md-12">
              <div class="x_panel">
                <div class="x_title">
                  <?php
                  if (!isset($_SESSION)) session_start();
                  if ($_SESSION['userNivel'] == 'ADMINISTRADOR' || $_SESSION['userNivel'] == 'CONSULTOR')
                    echo "<button class=\"btn btn-info\" id=\"btnExportarAgenda\">Exportar Agenda</button>";
                  ?>


                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <div class="legenda">
                    <h4>Legenda</h4>

                    <span class="fa fa-circle" id="confirmado"> Confirmado</span>
                    <span class="fa fa-circle" id="reservado"> Reservado</span>
                    <span class="fa fa-circle" id="remoto"> Remoto</span>
                    <span class="fa fa-circle" id="liberado"> Liberado</span>
                    <span class="fa fa-circle" id="exportado"> Encerrado</span>
                    <span class="fa fa-circle" id="particular"> Particular</span>
                  </div>
                  <div id='calendar'></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- /page content -->
      <!-- FOOTER -->
      <footer>
        <div class="pull-right">
          Compacta Dashboard desenvolvida por <a href="http://www.solucaocompacta.com.br">Solução Compacta</a>
        </div>
        <div class="clearfix"></div>
      </footer>
      <!-- /FOOTER -->

      <!-- footer content -->

    </div>
  </div>

  <!-- EXPORTAR AGENDA -->
  <div id="ExportarAgenda" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myModalLabel">Exportação de agenda</h4>
          <label id="txtCriador" hidden></label>
        </div>
        <div class="modal-body">
          <div id="testmodal" style="padding: 5px 20px;">
            <form id="formExportacao" action='ExportarAgenda.php' method="POST" target="_blank" class="form-horizontal form-label-left">
              <!-- Componente Data inicio -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Inicio período<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="dataInicio" name='dataInicio' class="date-picker form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99/99/9999'" type="text">
                </div>
              </div>

              <!-- Componente Data fim -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Fim período<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="dataFim" name='dataFim' class="date-picker form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99/99/9999'" placeholder="12/12/2016" type="text">
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-12 col-sm-12 col-xs-12">Os campos abaixo servem como filtros, se não quiser filtrar apenas ignore-os</label>
              </div>

              <!-- Componente consultor -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-6 col-xs-12">Consultor:</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select tabindex="-1" class="form-control col-md-3 col-sm-6 col-xs-12" name="filtroConsultor" id="filtroConsultor">
                    <option value=0>...</option>";
                    <?php
                    include_once "../scripts/Conexao-class.php";
                    $conect = new Conexao();
                    $link = $conect->getLink();
                    if (!isset($_SESSION)) session_start();

                          //se eh administrador ou consultor interno
                    $nivel = $_SESSION['userNivel'];
                    if ($nivel == 'ADMINISTRADOR')
                      $sql = "SELECT * FROM consultor WHERE CONS_TIPO != '' ORDER BY CONS_NOME";

                          //se eh consultor externo
                    if ($nivel == 'CONSULTOR')
                      $sql = "SELECT * FROM CONSULTOR WHERE CONS_ID=".$_SESSION['userId']." ORDER BY CONS_NOME";

                          //se eh usuario nao puxa nada
                    if ($nivel == 'USUÁRIO')
                      $sql = "SELECT * FROM CONSULTOR WHERE CONS_ID < 0 ORDER BY CONS_NOME";


                    $result = mysqli_query($link,$sql);
                    $i = 1;
                    while ($row = $result->fetch_assoc())
                    {
                      if ($row['CONS_NOME'] == 'ADMINISTRADOR') continue;
                      echo "<option value=".$row['CONS_ID']."> ".$row['CONS_NOME']." </option>";
                      $i++;
                    }
                    ?>
                  </select><br>
                </div>
              </div>

              <!-- Componente cliente -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Cliente:</label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <select tabindex="-1" class="form-control col-md-3 col-xs-3 col-sm-3" name="filtroCliente" id="filtroCliente">
                    <option value=0>...</option>";
                    <?php
                    include_once "../scripts/Conexao-class.php";
                    $conect = new Conexao();
                    $link = $conect->getLink();

                          //se eh administrador ou consultor interno
                    $nivel = $_SESSION['userNivel'];
                    if ($nivel == 'ADMINISTRADOR' || ($nivel == 'CONSULTOR' && $_SESSION['userTipo'] == 'INTERNO'))
                      $sql = "SELECT * FROM CLIENTES ORDER BY CLI_NREDUZ";

                          //se eh consultor externo
                    if ($nivel == 'CONSULTOR' && $_SESSION['userTipo'] == 'EXTERNO' || $nivel == 'USUÁRIO')
                      $sql = "SELECT * FROM CLIENTES WHERE CLI_ID=".$_SESSION['userEmpresa']." ORDER BY CLI_NREDUZ";

                    $result = mysqli_query($link,$sql);
                    $i = 1;
                    while ($row = $result->fetch_assoc())
                    {
                      echo "<option value=".$row['CLI_ID']."> ".$row['CLI_NREDUZ']." </option>"
                      ;
                      $i++;
                    }
                    ?>
                  </select>
                  <br><br>
                  <!-- Componente Cliente -->
                </div>
              </div>



              <button type="submit" id="btnExportarAjax" class="btn btn-primary antosubmit">Exportar</button>
              <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Fechar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /EXPORTAR AGENDA -->



  <!-- INSERÇÃO NA AGENDA -->
  <div id="CalenderModalNew" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h4 class="modal-title" id="myModalLabel">Adicionar novo evento</h4>
          <label id="txtCriador" hidden></label>
        </div>
        <div class="modal-body">
          <div id="testmodal" style="padding: 5px 20px;">
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="ordemdeservico.php" method="post">

              <!-- LABEL DE AVISO -->
              <label id="aviso"> Preencha todos os campos obrigatórios <span>(*)</span></label>

              <!-- DATA -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">Data:<span class="required">(*)</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="data" name="data" class="form-control col-md-6 col-xs-6" type="text" data-inputmask="'mask' : '99/99/9999'" placeholder='12/12/2016' required>
                </div>
              </div>

              <!--  INICIO -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12"> Hora inicio<span class="required">(*)</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="inicio" name="inicio" class="form-control col-md-6 col-xs-6" type="text" data-inputmask="'mask' : '99:99'" placeholder="08:00" value="08:00" required>
                </div>
              </div>

              <!-- FIM -->
              <div class="form-group"> <label class="control-label col-md-3 col-sm-3 col-xs-12">Hora fim<span class="required">(*)</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="fim" name="fim" class="form-control col-md-6 col-xs-6" type="text" data-inputmask="'mask' : '99:99'" placeholder="18:00" value="18:00" required>
              </div>
            </div>

            <!-- CLIENTE -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Selecione o cliente:<span class="required">(*)</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" tabindex="-1" name="cliente" id="cliente" required>
                  <option value='0'></option>
                  <?php
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
                  ?>
                </select>
              </div>
            </div>

            <!-- CONSULTOR -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Selecione o consultor:<span class="required">(*)</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" tabindex="-1" name="consultor" id="consultor" required>
                  <option value='0'></option>
                  <?php
                  include_once "../scripts/Conexao-class.php";
                  $conect = new Conexao();
                  $link = $conect->getLink();
                  $sql = "SELECT * FROM consultor WHERE CONS_TIPO != '' ORDER BY CONS_NOME";
                  $result = mysqli_query($link,$sql);
                  $i = 1;
                  while ($row = $result->fetch_assoc())
                  {
                    if ($row['CONS_NOME'] == 'ADMINISTRADOR') continue;
                    $modulo = str_replace("_", " ", $row['CONS_NOME']);
                    echo "<option value=\"".$row['CONS_ID']."\"> ".$modulo." </option>"
                    ;
                    $i++;
                  }
                  ?>
                </select>
              </div>
            </div>

            <!-- DESCRIÇÃO -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Descrição</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input class="form-control" style="height:55px;" id="descricao" name="descricao" maxLength="600" />
              </div>
            </div>

            <!-- TIPO -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo<span class="required">(*)</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id="tipo" name="tipo" class="form-control" required>
                  <option value="PRESENCIAL">PRESENCIAL</option>
                  <option value="REMOTO">REMOTO</option>
                  <option value="PARTICULAR">PARTICULAR</option>
                </select>
              </div>
            </div>

            <!-- STATUS -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Status<span class="required">(*)</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id="status" name="status" class="form-control" required>
                  <option value=1> CONFIRMADO </option>
                  <option value=0> RESERVADO</option>
                </select>
              </div>
            </div>

            <!-- FATURADO -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Faturado<span class="required">(*)</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id="faturado" name="faturado"  class="form-control" required>
                  <option value=1> SIM </option>
                  <option value=0> NÃO </option>
                </select>
              </div>
            </div>

            <!-- EH PROJETO? -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Projeto?<span class="required">(*)</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select id="ehProjeto" name="ehProjeto"  class="form-control" required>
                  <option value=1> SIM </option>
                  <option value=0> NÃO </option>
                </select>
              </div>
            </div>

            <script type="text/javascript">
              $(document).ready(function () {

                $('#ehProjeto').on('change', function() {
                  var valor = $('#ehProjeto option:selected').val();
                    //eh projeto
                    if (valor == 1)
                    {
                      $('#projeto').attr('required',true);
                    }
                    //nao eh projeto
                    else
                    {
                      $('#projeto').attr('required',false);
                    }
                  });
              });
            </script>
			
            <!-- IDPROJETO -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Código do projeto:</label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" tabindex="-1" name="projeto" id="projeto">
                  <option value = 0></option>
                  <?php
                  include_once "../scripts/Conexao-class.php";
                  $conect = new Conexao();
                  $link = $conect->getLink();
                  $sql = " SELECT * FROM cadastro_projetos WHERE PROJ_BLOQUEADO != 1 ";
                  $result = mysqli_query($link,$sql);
                  $i = 1;
                  while ($row = $result->fetch_assoc())
                  {
                    $modulo = str_replace("_", " ", $row['PROJ_DESCRICAO']);
                    echo "<option value=\"".$row['PROJ_ID']."\"> ".$modulo." </option>"
                    ;
                    $i++;
                  }
                  ?>
                </select>
              </div>
            </div>

            <!-- COORDENADOR -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Selecione o coordenador:<span class="required">(*)</span></label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <select class="form-control" tabindex="-1" name="coordenador" id="coordenador" required>
                  <option value='0'></option>
                  <?php
                  include_once "../scripts/Conexao-class.php";
                  $conect = new Conexao();
                  $link = $conect->getLink();
                  $sql = "SELECT * FROM consultor WHERE CONS_TIPO != '' ORDER BY CONS_NOME";
                  $result = mysqli_query($link,$sql);
                  while ($row = $result->fetch_assoc())
                  {
                    $modulo = str_replace("_", " ", $row['CONS_NOME']);
                    echo "<option value=\"".$row['CONS_ID']."\"> ".$modulo." </option>"
                    ;
                  }
                  ?>
                </select>
              </div>

              <input type="text" name="idAgenda" id="agendaId" value="0" hidden>

            </div>
            <?php
            if (!($_SESSION['userNivel'] == 'CONSULTOR' && $_SESSION['userTipo'] == 'EXTERNO'))
              echo "<button type=\"submit\" class=\"btn btn-warning\" id=\"btnOrdem\">Ordem de Serviço</button>";
            ?>
          </form>
          <?php
          if ($_SESSION['userNivel'] == 'ADMINISTRADOR')
          {
            echo "<button type=\"button\" id=\"antosubmit\" class=\"btn btn-primary\">Salvar na agenda</button>";
          }
          ?>
          <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Fechar</button>
          <button type="button" id="btnExcluir" class="btn btn-danger" data-dismiss="modal">Excluir</button>

        </div>
      </div>
    </div>
  </div>
</div>
<!-- /INSERÇÃO NA AGENDA -->



<div id="fc_create" data-toggle="modal" data-target="#CalenderModalNew"></div>

<div id="fc_edit" data-toggle="modal" data-target="#ExportarAgenda"></div>

<!-- /calendar modal -->

<!-- exibe modal de exportacao -->
<script type="text/javascript">
  $(document).ready(function() {
    $('#btnExportarAgenda').on('click', function(){
      $('#fc_edit').click();
    })
  })
</script>

<!-- jQuery -->
<script src="../vendors/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap -->
<script src="../vendors/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- FastClick -->
<script src="../vendors/fastclick/lib/fastclick.js"></script>
<!-- NProgress -->
<script src="../vendors/nprogress/nprogress.js"></script>
<!-- FullCalendar -->
<script src="../vendors/moment/min/moment.min.js"></script>
<script src="../vendors/fullcalendar/dist/fullcalendar.min.js"></script>
<!-- Select2 -->
<script src="../vendors/select2/dist/js/select2.full.min.js"></script>
<!-- jquery.inputmask -->
<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
<!-- Custom Theme Scripts -->
<script src="../build/js/custom.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="../vendors/moment/min/moment.min.js"></script>
<script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- PNotify -->
<script src="../vendors/pnotify/dist/pnotify.js"></script>
<script src="../vendors/pnotify/dist/pnotify.buttons.js"></script>
<script src="../vendors/pnotify/dist/pnotify.nonblock.js"></script>
<!-- Parsley -->
<script src="../vendors/parsleyjs/dist/parsley.js"></script>

<!-- data de exportacao padrao -->
<script type="text/javascript">
  function anoBissexto (ano) {
    return new Date(ano, 1, 29).getMonth();
  }

  function diaFim (mes, ano) {
    if (mes == 1 || mes == 3 || mes == 5 || mes == 7 || mes == 8 || mes == 10 || mes == 12)
      return 31;
    else if (mes == 2) {
      var mesBissexto = anoBissexto(ano);
      if (mesBissexto == 1) return 29;
      else return 28;
    }
    else
      return 30;
  }

  $(document).ready(function() {
    var data = new Date();
    var mes = data.getMonth();
    var ano = data.getFullYear();
    // padronizando mes
    mes += 1;

    var diaInicio = "01";
    var diaFinal  = diaFim(mes, ano);

    var mesString = "";
    if(mes < 10) mesString = "0"+mes;
    else mesString = mes;

    var dataInicio = diaInicio + "/" + mesString + "/" + ano;
    var dataFim = diaFinal + "/" + mesString + "/" + ano;

    $('#dataInicio').val(dataInicio);
    $('#dataFim').val(dataFim);
  })
</script>
<!-- /data de exportacao padrao -->

<!-- atualizacao dos campos hidden -->
<script type="text/javascript">
  $(document).ready(function(){
    $('#inicioHidden').val($('#inicio').val());
    $('#fimHidden').val($('#fim').val());

    $('#inicio').on('input', function(){
      $('#inicioHidden').val($('#inicio').val());
    });

    $('#fim').on('input', function(){
      $('#fimHidden').val($('#fim').val());
    });

    $('#data').on('change', function(){
      $('#dataHidden').val($('#data').val());
    });

    $('#cliente').on('change', function(){
      $('#clienteHidden').val($('#cliente').val());
    });

    $('#consultor').on('change', function(){
      $('#consultorHidden').val($('#consultor').val());
    });

  })
</script>

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
<!-- bootstrap-daterangepicker -->
<script>
  $(document).ready(function() {
    $('#dataInicio').daterangepicker({
      singleDatePicker: true,
      calender_style: "picker_3"
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
  });
</script>
<!-- /bootstrap-daterangepicker -->
<!-- bootstrap-daterangepicker -->
<script>
  $(document).ready(function() {
    $('#dataFim').daterangepicker({
      singleDatePicker: true,
      calender_style: "picker_3"
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
  });
</script>
<!-- /bootstrap-daterangepicker -->
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

<!-- jquery.inputmask -->
<script>
  $(document).ready(function() {
    $(":input").inputmask();
  });
</script>
<!-- /jquery.inputmask -->

<!-- on ajax send-->
<script type="text/javascript">
  $(document).ajaxSend(function(){
    $('#antosubmit').attr('disabled','true');
    $('#antoclose').click();
    window.location.reload();
  });
</script>

<script>
    //setar o campo deletar agenda
    $(document).ready(function() {
      $('#btnExcluir').on('click', function(){
        var idAgenda = $('#agendaId').val();
        var cliente = $('#cliente').val();
        var consultor = $('#consultor').val();
        var status = $('#status').val();
        var coordenador = $('#coordenador').val();
        var faturado = $('#faturado').val();
        var inicio = $('#inicio').val();
        var fim = $('#fim').val();
        var data = $('#data').val();
        var descricao = $('#descricao').val();
        var tipo = $('#tipo').val();
        var projeto = $('#projeto').val();

        $.ajax({
          type: 'POST',
          url: '../scripts/cadAgenda.php',
          data:
          {
            deletarAgenda: idAgenda,
            data: data,
            cliente: cliente,
            consultor: consultor,
            inicio: inicio,
            fim: fim,
            descricao: descricao,
            coordenador: coordenador,
            faturado: faturado,
            status: status,
            projeto: projeto
          },
          success: function (){
            location.reload();
          },
          error: function (result){
            alert(result);
          }
        });
      });
    });

    $('#antosubmit').on('click', function(){
      var data = $('#data').val();
      var vetorData = data.split('/');
      var data2 = vetorData[0]+"/"+vetorData[1];
      var cliente = $('#cliente').val();

      $.ajax({
        type: 'POST',
        url: 'checaFeriado.php',
        data: {
          cliente: cliente,
          data: data2
        },
        success: function(result){
          if (result == '1') {
            var confirma = confirm ('A data especificada é um feriado para este cliente, deseja continuar mesmo assim?');
            if (confirma) antosubmit();
          } else {
            antosubmit();
          }
        },
        error: function(result){
          alert(result);
        }
      });
    });

    function salvarAgenda(){
      antosubmit();
    }

    function antosubmit()
    {
     var cliente     = $('#cliente').val();
     var consultor   = $('#consultor').val();
     var status      = $('#status').val();
     var coordenador = $('#coordenador').val();
     var faturado    = $('#faturado').val();
     var inicio      = $('#inicio').val();
     var fim         = $('#fim').val();
     var tipo        = $('#tipo').val();
     var data        = $('#data').val();
     var descricao   = $('#descricao').val();
     var projeto     = $('#projeto option:selected').val();
     var idAgenda    = $('#agendaId').val();

     var ehProjeto = $('#ehProjeto option:selected').val();

          //faz validações
          if (cliente == 0 || consultor == 0 || status == -1 || coordenador == 0 || faturado == -1 || inicio=="" || fim == "" || (ehProjeto == 1 && projeto == 0))
          {

           $('#aviso').css('display','block');
           $('#aviso').css('color','#f33');
           if (ehProjeto == 1)
            $('#projeto').css('background-color','#fab');
          if (inicio == "")
            $('#inicio').css('background-color','#fab');
          if (fim == "")
            $('#fim').css('background-color','#fab');
          if (cliente == 0)
            $('#cliente').css('background-color','#fab');
          if (consultor == 0)
            $('#consultor').css('background-color','#fab');
          if (status == -1)
            $('#status').css('background-color','#fab');
          if (faturado == -1)
            $('#faturado').css('background-color','#fab');
          if (coordenador == 0)
            $('#coordenador').css('background-color','#fab');
        }
        else
        {
            //chama o ajax
            $.ajax({
              type: 'POST',
              url: '../scripts/cadAgenda.php',
              data:
              {
                data: data,
                cliente: cliente,
                consultor: consultor,
                inicio: inicio,
                fim: fim,
                tipo: tipo,
                descricao: descricao,
                coordenador: coordenador,
                faturado: faturado,
                status: status,
                projeto: projeto,
                idAgenda: idAgenda
              },
              success: function(){

                 $('#antoclose').click();
                 alert('Agenda salva, atualize a página para visualizar!');
                 window.location.replace('agenda.php');
              },
              error: function(result){

                $('#antoclose').click();
                window.location.replace('agenda.php');
              }
            });
            //fim ajaX
          }
        }

        $(document).ready(function() {
          var date = new Date(),
          d = date.getDate(),
          m = date.getMonth(),
          y = date.getFullYear(),
          started,
          categoryClass;

    //cria a agenda
    var calendar = $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay,listWeek'
      },
      droppable: true,
      selectable: true,
      selectHelper: true,
      eventLimit: true,
      editable: true,
      //select para inserir um evento
      select: function(start, end, allDay) {
        $('#btnOrdem').css('display','none');
        $('#txtCriador').attr('hidden', true);
        $('#status').val('1').change();
        //exibe o modal de inserção
        $('#fc_create').click();
        var clienteSession = "<?php echo $_SESSION['filtroCliente'] ?>";
        if (clienteSession != "0")
          $('#cliente').val(clienteSession);
        var consultorSession = "<?php echo $_SESSION['filtroConsultor'] ?>";
        if (consultorSession != "0")
          $('#consultor').val(consultorSession);
        else
          $('#consultor').val('0');

        $('#descricao').val('');
        $('#coordenador').val('45');
        $('#faturado').val('1');
        $('#projeto').val('0');
        $('#ehProjeto').val('0');
        $('#agendaId').val('0');
        $('#inicio').val('08:00');
        $('#fim').val('18:00');
        $('#data').val(start.format("DD/MM/YYYY"));
        $('#btnExcluir').css('display', 'none');
        $('#aviso').attr('hidden',true);
        $('#inicio').css('background-color','#fff');
        $('#fim').css('background-color','#fff');
        $('#cliente').css('background-color','#fff');
        $('#consultor').css('background-color','#fff');
        $('#status').css('background-color','#fff');
        $('#faturado').css('background-color','#fff');
        $('#coordenador').css('background-color','#fff');
        $('#tipo').prop('disabled', false);
        //pega o moment para inserir o evento na agenda

        <?php
        if ($_SESSION['userNivel'] != 'ADMINISTRADOR')
        {
          echo "
          $('#cliente').attr('disabled', true);
          $('#tipo').attr('disabled', true);
          $('#descricao').attr('disabled', true);
          $('#consultor').attr('disabled', true);
          $('#faturado').attr('disabled', true);
          $('#projeto').attr('disabled', true);
          $('#data').attr('disabled', true);
          $('#inicio').attr('disabled', true);
          $('#fim').attr('disabled', true);
          $('#status').attr('disabled', true);
          $('#projeto').attr('disabled', true);
          $('#ehProjeto').attr('disabled', true);
          $('#coordenador').attr('disabled', true);
          ";
        }
        ?>

        started = start;
        ended = end;

        //$('#antosubmit').click();

        categoryClass = $("#event_type").val();
        return false;
      },
      dayRender: function (date, cell) {
        var data = moment(date).format('DD/MM');
        var arrayFeriado = "<?php echo $listaFeriados?>";
        arrayFeriado = arrayFeriado.split(";");
        var i;
        for (i = 0; arrayFeriado[i] != ""; i++){
          if (arrayFeriado[i] == data){
            cell.css("background-color", "#ffc4c4");
            cell.css("value", "feriado");
          }
        }
      },
      eventMouseover: function(data, jsEvent,view){
        var descricao = data.descricao;
        while (descricao.indexOf("&#13") > 0)
        {
          descricao = descricao.replace("&#13;","<br>");
        }

        tooltip = '<div class="tooltiptopicevent" style="width:auto;height:auto;background:rgba(255,255,255,0.8);position:absolute;z-index:10001;padding:10px 10px 10px 10px ;  line-height: 200%;">'+descricao+'</div>';


        $("body").append(tooltip);
        $(this).mouseover(function (e) {
          $(this).css('z-index', 10000);
          $('.tooltiptopicevent').fadeIn('500');
          $('.tooltiptopicevent').fadeTo('10', 1.9);
        }).mousemove(function (e) {
          $('.tooltiptopicevent').css('top', e.pageY + 10);
          $('.tooltiptopicevent').css('left', e.pageX + 20);
        });
      },
      eventMouseout: function (data, event, view) {
        $(this).css('z-index', 8);

        $('.tooltiptopicevent').remove();

      },
      dayClick: function () {
       $('.tooltiptopicevent').remove();

     },
     eventResizeStart: function () {
       $('.tooltiptopicevent').remove();

     },
     eventDragStart: function () {
      $('.tooltiptopicevent').remove();

    },
    viewDisplay: function () {
      $('.tooltiptopicevent').remove();

    },
      //eventClick Para atualizar um evento
      eventClick: function(calEvent, jsEvent, view) {
        /*busca informações deste evento para preencher o formulário*/
        if (calEvent.exportado != 1)
        {
          agendaCriador = calEvent.criador;
          var criadorTexto = "Agendado por: " + calEvent.criadorNome + " - Em: " + calEvent.dataInclusao;
          $('#txtCriador').html(criadorTexto);
          $('#btnOrdem').css('display','block');
          $('#txtCriador').attr('hidden', false);
          $('#dataHidden').val($('#data').val());
          $('#btnExcluir').css('display','none');
          $('#aviso').css('display','none');
          $('#fc_create').click();
          $('#cliente').val(calEvent.cliente);
          $('#tipo').val(calEvent.tipo);
          $('#descricao').val(calEvent.descricao);
          $('#consultor').val(calEvent.consultor);
          $('#status').val(calEvent.status);
          $('#coordenador').val(calEvent.coordenador);
          $('#faturado').val(calEvent.faturado);
          $('#projeto').val(calEvent.projeto);
          $('#inicio').val(calEvent.inicio);
          $('#fim').val(calEvent.fim);
          $('#inicioHidden').val(calEvent.inicio);
          $('#fimHidden').val(calEvent.fim);
          $('#dataHidden').val(calEvent.data);
          $('#clienteHidden').val(calEvent.cliente);
          $('#consultorHidden').val(calEvent.consultor);

          if(calEvent.liberado != '0000-00-00') {
            $('#data').attr('disabled',true);
            $('#tipo').attr('disabled',true);
            $('#inicio').attr('disabled', true);
            $('#fim').attr('disabled', true);
            $('#consultor').attr('disabled', true);
            $('#cliente').attr('disabled', true);
            $('#descricao').attr('disabled', true);
          }
          else
            $('#tipo').prop('disabled',false);


          if (calEvent.projeto < 1)
          {
            $('#ehProjeto').val('0');
          }
          $('#agendaId').val(calEvent.idAgenda);
          $('#data').val(calEvent.data);

          <?php
          if ($_SESSION['userNivel'] != 'ADMINISTRADOR')
          {
            echo "
            $('#cliente').attr('disabled', true);
            $('#tipo').attr('disabled', true);
            $('#descricao').attr('disabled', true);
            $('#consultor').attr('disabled', true);
            $('#faturado').attr('disabled', true);
            $('#projeto').attr('disabled', true);
            $('#data').attr('disabled', true);
            $('#inicio').attr('disabled', true);
            $('#fim').attr('disabled', true);
            $('#status').attr('disabled', true);
            $('#projeto').attr('disabled', true);
            $('#ehProjeto').attr('disabled', true);
            $('#coordenador').attr('disabled', true);
            ";
          }
          $nivelUser = "ADMINISTRADOR";
          ?>
          var nivelUser = "<?php echo $nivel?>";
          var liberado = calEvent.liberado;
          var criador = "<?php echo $_SESSION['userId']?>";

          if (nivelUser == 'ADMINISTRADOR' && liberado == '0000-00-00')
          {
            $('#btnExcluir').css('display','');
          }

          categoryClass = $("#event_type").val();

        //$('#antosubmit').click();
        //antosubmit
        return false;
      }
      else
      {
       window.location.replace(calEvent.linkPDF);
     }
   },
      //Eventos já cadastrados no banco de dados serão exibidos aqui:
      events: [
      <?php
      if (!isset($_SESSION)) session_start();
      include_once "../scripts/Conexao-class.php";
      include_once "../scripts/ConversorData-class.php";
      $idUsuario = $_SESSION['userId'];
      $conversor = new ConversorData;
      $conect = new Conexao();
      $link = $conect->getLink();
      $black = "#333";
      $sql =
      "SELECT
      AGEN_DTAGENDA, AGEN_HRINICIO, AGEN_HRFIM, AGEN_CLIENTE_ID, AGEN_STATUS, AGEN_PROJETO, AGEN_CONSULTOR, AGEN_COORDENADOR, AGEN_DESCRICAO, AGEN_ID, AGEN_FATURADO, AGEN_CRIADOR, AGEN_DTINCLUSAO, AGEN_TIPO, AGEN_LIBERADO, AGEN_EXPORTADO, AGEN_LINKPDF
      FROM
      AGENDA
      WHERE AGEN_CONSULTOR=".$idUsuario;

      if ($_SESSION['userNivel'] == 'ADMINISTRADOR')
        $sql = "SELECT
      AGEN_DTAGENDA, AGEN_HRINICIO, AGEN_HRFIM, AGEN_CLIENTE_ID, AGEN_STATUS, AGEN_PROJETO, AGEN_CONSULTOR, AGEN_COORDENADOR, AGEN_DESCRICAO, AGEN_ID, AGEN_FATURADO, AGEN_CRIADOR, AGEN_DTINCLUSAO, AGEN_TIPO, AGEN_LIBERADO, AGEN_EXPORTADO, AGEN_LINKPDF
      FROM
      AGENDA";

      /* Dentro deste espaço alteramos a query com base no método GET asdf*/
      if (isset($_GET['filtroCliente']) || isset($_GET['filtroConsultor']) || ($_SESSION['filtroCliente'] != 0 || $_SESSION['filtroConsultor'] != 0))
      {
        $consultor = $_SESSION['filtroConsultor'];
        $cliente = $_SESSION['filtroCliente'];

        if (isset($_GET['filtroCliente']) && $_GET['filtroCliente'] != "")
          $cliente = $_GET['filtroCliente'];

        if (isset($_GET['filtroConsultor']) && $_GET['filtroConsultor'] != "")
          $consultor = $_GET['filtroConsultor'];

        if ($_SESSION['userNivel'] == 'ADMINISTRADOR')
        {
          if ($cliente == "" || $cliente == "0")
          {
            $sql =
            "SELECT
            AGEN_DTAGENDA, AGEN_HRINICIO, AGEN_HRFIM, AGEN_CLIENTE_ID, AGEN_STATUS, AGEN_PROJETO, AGEN_CONSULTOR, AGEN_COORDENADOR, AGEN_DESCRICAO, AGEN_ID, AGEN_FATURADO,AGEN_CRIADOR, AGEN_DTINCLUSAO, AGEN_TIPO, AGEN_LIBERADO, AGEN_EXPORTADO, AGEN_LINKPDF
            FROM
            AGENDA
            WHERE AGEN_CONSULTOR=".$consultor;
          }
          else if ($consultor == "" || $consultor == "0")
          {
            $sql =
            "SELECT
            AGEN_DTAGENDA, AGEN_HRINICIO, AGEN_HRFIM, AGEN_CLIENTE_ID, AGEN_STATUS, AGEN_PROJETO, AGEN_CONSULTOR, AGEN_COORDENADOR, AGEN_DESCRICAO, AGEN_ID, AGEN_FATURADO,AGEN_CRIADOR, AGEN_DTINCLUSAO, AGEN_TIPO, AGEN_LIBERADO, AGEN_EXPORTADO, AGEN_LINKPDF
            FROM
            AGENDA
            WHERE AGEN_CLIENTE_ID=".$cliente;
          }
          else
          {
            $sql =
            "SELECT
            AGEN_DTAGENDA, AGEN_HRINICIO, AGEN_HRFIM, AGEN_CLIENTE_ID, AGEN_STATUS, AGEN_PROJETO, AGEN_CONSULTOR, AGEN_COORDENADOR, AGEN_DESCRICAO, AGEN_ID, AGEN_FATURADO,AGEN_CRIADOR, AGEN_DTINCLUSAO, AGEN_TIPO, AGEN_LIBERADO, AGEN_EXPORTADO, AGEN_LINKPDF
            FROM
            AGENDA
            WHERE AGEN_CONSULTOR=".$consultor." AND AGEN_CLIENTE_ID=".$cliente;
          }
        }
        else //se eh consultor
        {
         $sql =
         "SELECT
         AGEN_DTAGENDA, AGEN_HRINICIO, AGEN_HRFIM, AGEN_CLIENTE_ID, AGEN_STATUS, AGEN_PROJETO, AGEN_CONSULTOR, AGEN_COORDENADOR, AGEN_DESCRICAO, AGEN_ID, AGEN_FATURADO,AGEN_CRIADOR, AGEN_DTINCLUSAO, AGEN_TIPO, AGEN_LIBERADO, AGEN_EXPORTADO, AGEN_LINKPDF
         FROM
         AGENDA
         WHERE AGEN_CONSULTOR=".$_SESSION['userId'];

         if ($cliente != "0" && $cliente != ""){
          $sql .= " AND AGEN_CLIENTE_ID=".$cliente;

        }
      }
    }
    /* Dentro deste espaço alteramos a query com base no método GET*/

    if($_SESSION['userNivel'] == "CONSULTOR" && $_SESSION['userTipo'] == "EXTERNO")
      $sql =  "SELECT
    AGEN_DTAGENDA, AGEN_HRINICIO, AGEN_HRFIM, AGEN_CLIENTE_ID, AGEN_STATUS, AGEN_PROJETO, AGEN_CONSULTOR, AGEN_COORDENADOR, AGEN_DESCRICAO, AGEN_ID, AGEN_FATURADO,AGEN_CRIADOR, AGEN_DTINCLUSAO, AGEN_TIPO, AGEN_LIBERADO, AGEN_EXPORTADO, AGEN_LINKPDF
    FROM
    AGENDA
    WHERE AGEN_CLIENTE_ID=".$_SESSION['userEmpresa'];

    $result = mysqli_query($link, $sql);
    $eventos= "";

    if(mysqli_num_rows($result) > 0)
      while ($compromisso = $result->fetch_assoc())
      {
        $idCliente = $compromisso['AGEN_CLIENTE_ID'];
        $sqlAux = "SELECT CLI_NREDUZ FROM CLIENTES WHERE CLI_ID=".$idCliente;
        $resultAux = mysqli_query($link, $sqlAux);
        $nomeCliente = $resultAux->fetch_assoc();
        $nomeCliente = $nomeCliente['CLI_NREDUZ'];
        $color = 'gray';
        if ($compromisso['AGEN_STATUS'] == 1)
          $color = '#3bb300';
        if ($compromisso['AGEN_TIPO'] == 'REMOTO')
          $color = $black;
          if ($compromisso['AGEN_TIPO'] == 'PARTICULAR' || $compromisso['AGEN_CLIENTE_ID'] == 99)
            $color = '#a142f4';

        $sqlAux = "SELECT USER_NOME FROM USUARIO WHERE USER_ID=".$compromisso['AGEN_CRIADOR'];
        $resultAux = mysqli_query($link, $sqlAux);
        $criador = $resultAux->fetch_assoc();
        $criador = $criador['USER_NOME'];

        $nomeConsultor = $compromisso['AGEN_CONSULTOR'];
        $sql = "SELECT USER_LOGIN FROM USUARIO WHERE USER_ID=".$nomeConsultor;
        $resultAux = mysqli_query($link, $sql);
        $nomeConsultor=$resultAux->fetch_assoc();
        $nomeConsultor = $nomeConsultor['USER_LOGIN'];
        $nomeConsultor = substr($nomeConsultor, 0, strpos($nomeConsultor, "@"));

        $inicio = $compromisso['AGEN_HRINICIO'];
        $fim = $compromisso['AGEN_HRFIM'];

        $descricao = $compromisso['AGEN_DESCRICAO'];
        $descricao = str_replace("'", "\'", $descricao);

        if ($compromisso['AGEN_LIBERADO'] != '0000-00-00')
          $color = "#ffd600";
        if ($compromisso['AGEN_EXPORTADO'] != '0000-00-00')
          $color = "#ff291f";

        $exportado = 0;
        if ($compromisso['AGEN_EXPORTADO'] != "0000-00-00")
          $exportado = 1;

        $linkPDF = "";

        if ($compromisso['AGEN_LINKPDF'] == '')
          $linkPDF = "osPDF.php?id=".$compromisso['AGEN_ID'];
        else
          $linkPDF = $compromisso['AGEN_LINKPDF'];


        $eventos .=
        "{
          title: '".$nomeConsultor." - ".$nomeCliente."',
          start: '".$conversor->sql2Agenda($compromisso['AGEN_DTAGENDA'])."',
          data: '".$conversor->sql2Brasil($compromisso['AGEN_DTAGENDA'])."',
          color: '".$color."',
          inicio: '".$inicio."',
          fim: '".$fim."',
          cliente: '".$compromisso['AGEN_CLIENTE_ID']."',
          status: '".$compromisso['AGEN_STATUS']."',
          projeto: '".$compromisso['AGEN_PROJETO']."',
          consultor: '".$compromisso['AGEN_CONSULTOR']."',
          coordenador: '".$compromisso['AGEN_COORDENADOR']."',
          faturado: '".$compromisso['AGEN_FATURADO']."',
          descricao: '".$compromisso['AGEN_DESCRICAO']."',
          idAgenda: ".$compromisso['AGEN_ID'].",
          criadorNome: '".$criador."',
          criador: '".$compromisso['AGEN_CRIADOR']."',
          dataInclusao: '".$compromisso['AGEN_DTINCLUSAO']."',
          tipo: '".$compromisso['AGEN_TIPO']."',
          idAgenda: '".$compromisso['AGEN_ID']."',
          exportado: '".$exportado."',
          liberado: '".$compromisso['AGEN_LIBERADO']."',
          linkPDF: '".$linkPDF."'
        },";
      }

      $eventos = substr($eventos, 0, strlen($eventos) - 1);
      echo $eventos;

      ?>

      ]
    });
});
</script>
<!-- /FullCalendar -->
<script type="text/javascript">
  $(document).ready(function () {
    <?php
    if(isset($_GET['filtroCliente']) || isset($_GET['filtroConsultor'])){
      if ($_GET['filtroCliente'] == "")
       echo "
     $('#filtroConsultor').val('".$consultor."').change();
     ";
     else if ($_GET['filtroConsultor'] == "")
       echo "
     $('#filtroCliente').val('".$cliente."');
     ";
     else
       echo "
     $('#filtroConsultor').val('".$consultor."');
     $('#filtroCliente').val('".$cliente."');
     ";
   }
   ?>

 });

</script>

</body>
</html>
