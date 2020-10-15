<!-- HOMEPAGE -->
<?php
if (!isset($_SESSION)) session_start();

if ($_SESSION['userNivel'] == 'USUÁRIO' || (isset($_SESSION['userTipo']) && $_SESSION['userTipo'] == "EXTERNO"))
{
  echo "<script> window.location.replace('ocorrencias.php'); </script>";
}

include_once "../scripts/Conexao-class.php";
$conect = new Conexao();
$link = $conect->getLink();

$sql = "SELECT DISTINCT
OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_DTPRAZO
FROM
ocorrencias
INNER JOIN acessos
ON acessos.ACE_ID_MODULOS = ocorrencias.OCOR_ID_MODULOS
INNER JOIN usuario
ON usuario.USER_ID = acessos.ACE_USUARIO_ID
and usuario.USER_BLOQUEADO <> 0 
WHERE
usuario.USER_ID = ".$_SESSION['userId']."
AND
ocorrencias.OCOR_DTENCERRAMENTO = 0
AND
ocorrencias.OCOR_CONSULTOR = ".$_SESSION['userId'].
"";
$qtdeOcorrencias = mysqli_query($link, $sql);
$qtdeOcorrencias2 = mysqli_num_rows($qtdeOcorrencias);

//testes nova funcao
include_once "ChecaAtraso.class.php";
$checaAtraso = new ChecaAtraso();
if($checaAtraso->checaAtrasoEvento()) $temEventoAtrasado = true;
else $temEventoAtrasado = false;
if($checaAtraso->checaAtrasoChamado()) $temChamadoAtrasado = true;
else $temChamadoAtrasado = false;
if($checaAtraso->checaAtrasoOS()) $temOSAtrasada = true;
else $temOSAtrasada = false;

$mensagemAtraso = "Atrasos pendentes em:";

if ($temEventoAtrasado) $mensagemAtraso .= "<br>-Eventos";
if ($temChamadoAtrasado) $mensagemAtraso .= "<br>-Ocorrências";
if ($temOSAtrasada) $mensagemAtraso .= "<br>-Ordem de serviço";

# FLAG PARA MUDAR OU NAO A QUERY QUE BUSCA OS EVENTOS DO CONSULTOR
$mostrarAtrasados = false;
if ($temChamadoAtrasado || $temEventoAtrasado || $temOSAtrasada) {
  $mostrarAtrasados = true;
}

$mensagemAtraso .= "<br>reprograme seus atrasos para poder fazer novos assentamentos, cadastrar novas ocorrências, liberar OS's não atrasadas e prosseguir com sua agenda diária.";

// checa feriados
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


  <title>Solução Compacta</title>

  <!-- Bootstrap -->
  <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- iCheck -->
  <link href="../vendors/iCheck/skins/flat/green.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="../vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
  <!-- bootstrap-wysiwyg -->
  <link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
  <!-- PNotify -->
  <link href="../vendors/pnotify/dist/pnotify.css" rel="stylesheet">
  <link href="../vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
  <link href="../vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
  <!-- FullCalendar -->
  <link href="../vendors/fullcalendar/dist/fullcalendar.min.css" rel="stylesheet">
  <link href="../vendors/fullcalendar/dist/fullcalendar.print.css" rel="stylesheet" media="print">
  <!-- Select2 -->
  <link href="../vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <!-- Custom Theme Style -->
  <link href="../build/css/custom.min.css" rel="stylesheet">

  <!-- LOADER -->
  <link href='loader.css' rel='stylesheet' type='text/css'>
  <link href='tooltip.css' rel='stylesheet' type='text/css'>

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
     <!-- MONTA MENUS -->
     <?php
     include_once "MontaMenu.php";
     $menu = new MontaMenu();
     ?>
     <!-- FIM MONTAGEM DE MENUS -->

     <!-- CONTEÚDO DA PÁGINA-->
     <div class="right_col" role="main">
      <div class="">
        <!-- TÍTULO -->
        <div class="page-title">
          <div class="title_left">
           <h3>Página do usuário</h3>
         </div>
       </div>
       <div class="title_right">
         <div class="col-md-5 col-sm-5 col-xs-12 form-group pull-right top_search">
         </div>
       </div>

       <BR><BR>
         <!-- AGENDA -->
         <div class="col-md-12">
          <div class="x_panel">
            <div class="x_title">
              <h2>Eventos para atendimentos <small>Agenda</small></h2>
              <div class="clearfix"></div>
              <label>Legenda: </label>
              <label style="color: #f44"><span class="fa fa-circle" style="color: #f44"></span> Atrasado</label>
              <label style="color: #89f"><span class="fa fa-circle" style="color: #89f"></span> Implantação</label>
              <label style="color: #8f9"><span class="fa fa-circle" style="color: #8f9"></span> Helpdesk</label>
              <label style="color: #000"><span class="fa fa-circle" style="color: #000"></span> Desenvolvimento</label>

              <div class="clearfix"></div>
              <form>
                <div id='filtroGet' class="form-group" hidden>
                  <?php
                  if($_SESSION['userNivel'] == 'ADMINISTRADOR' || $_SESSION['userNivel'] == 'CONSULTOR')
                  {
                    echo "<!-- Componente consultor -->
                    <label class=\"control-label col-md-1 col-sm-6 col-xs-12\">Consultor:</label>
                    <div class=\"col-md-3 col-sm-3 col-xs-12\">
                      <select tabindex=\"-1\" class=\"form-control col-md-3 col-sm-6 col-xs-12\" name=\"filtroConsultor\" id=\"filtroConsultor\"  onchange=\"filtrarAgenda()\">
                        <option></option>
                        ";
                        include_once "../scripts/Conexao-class.php";
                        $conect = new Conexao();
                        $link = $conect->getLink();
                        $sql = "SELECT * FROM CONSULTOR WHERE CONS_BLOQUEADO <> 0 ";
                        $result = mysqli_query($link,$sql);
                        $i = 1;
                        while ($row = $result->fetch_assoc())
                        {
                          echo "<option value=\"".$row['CONS_ID']."\"> ".$row['CONS_NOME']." </option>"
                          ;
                          $i++;
                        }
                        echo "</select><br>
                      </div>";
                    }
                    ?>

                    <!-- Componente Cliente -->
                    <label class="control-label col-md-1 col-sm-6 col-xs-12">Cliente:</label>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <select tabindex="-1" class="form-control col-md-3 col-xs-3 col-sm-3" name="filtroCliente" id="filtroCliente" onchange="filtrarAgenda()">
                        <option></option>
                        <?php
                        include_once "../scripts/Conexao-class.php";
                        $conect = new Conexao();
                        $link = $conect->getLink();
                        $sql = "SELECT * FROM clientes";
                        $result = mysqli_query($link,$sql);
                        $i = 1;
                        while ($row = $result->fetch_assoc())
                        {
                          echo "<option value=\"".$row['CLI_ID']."\"> ".$row['CLI_NOME']." </option>"
                          ;
                          $i++;
                        }
                        ?>
                      </select>
                      <br><br>

                    </div>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                      <button class="btn btn-primary">Ok</button>
                      <a href="home.php" class="btn btn-info">Limpar filtro</a>
                    </div>
                  </div>
                </form>
              </div>

              <div class="x_content">
                <div id='calendar'></div>
              </div>

            </div>
          </div>
          <!-- AGENDA -->
          <!-- OCORRENCIAS -->
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="x_panel">
              <div class="x_title">
                <h2>Chamados não finalizados <small>ocorrências</small></h2>
                <ul class="nav navbar-right panel_toolbox">
                  <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                  </li>
                </ul>
                <div class="clearfix"></div>
              </div>
              <div class="x_content">
                <ul class="list-unstyled timeline">
                  <!-- INICIO BLOCO OCORRENCIA-->
                  <?php
                  include_once "../scripts/Conexao-class.php";
                  $conect = new Conexao();
                  $link = $conect->getLink();

                  $sql = "
                  SELECT DISTINCT
                  OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_DESCRICAO, OCOR_ID_CLIENTE, OCOR_DTINCLUSAO
                  FROM ocorrencias
                  INNER JOIN
                  acessos
                  ON
                  acessos.ACE_ID_MODULOS = ocorrencias.OCOR_ID_MODULOS
                  INNER JOIN
                  usuario
                  ON
                  usuario.USER_ID = acessos.ACE_USUARIO_ID
                  WHERE
                  OCOR_DTPRAZO != 0
                  AND
                  OCOR_DTENCERRAMENTO = 0
                  AND
                  ACE_BLOQUEADO != 1
                  AND
                  USER_ID = ".$_SESSION['userId']."
                  ";
                  $qtdeOcorrencias = mysqli_query($link, $sql);

                  if (mysqli_num_rows($qtdeOcorrencias) == 0)
                  {
                    echo "<p align=center>
                    Nenhuma ocorrência encontrada em nossa base para você
                  </p>
                  ";
                }

                while ($row = $qtdeOcorrencias->fetch_assoc())
                {
                 echo "<li>
                 <div class=\"block\">
                  <div class=\"tags\">
                    <a href=\"assentamento.php?idocorr=".$row['OCOR_ID']."\" class=\"tag\">";
                      $sql = "SELECT CLI_NOME FROM clientes WHERE CLI_ID=".$row['OCOR_ID_CLIENTE'];
                      $result = mysqli_query($link, $sql);
                      $cliente = $result->fetch_assoc();
                      echo "<span> (".$row['OCOR_ID'].") ".$cliente['CLI_NOME']."</span>
                    </a>
                  </div>
                  <div class=\"block_content\">
                    <h2 class=\"title\">
                      <a>".$row['OCOR_DESC_RESUMIDA']."</a>
                    </h2>
                    <div class=\"byline\">
                      <span>".$row['OCOR_DTINCLUSAO']."</span> por <a> ".$cliente['CLI_NOME']."</a>
                    </div>
                    <p > ".$row['OCOR_DESCRICAO']."
                    </p>
                  </div>
                </div>
              </li>";
              mysqli_free_result($result);
            }
            ?>
            <!-- FIM BLOCO OCORRENCIA-->
          </ul>
        </div>
      </div>
    </div>

    <!-- /OCORRENCIAS -->
    <div class="col-md-6 col-sm-6 col-xs-12">
      <div class="x_panel">
        <div class="x_title">
          <h2> Chamados sem atendimento <small>ocorrências</small></h2>
          <ul class="nav navbar-right panel_toolbox">
            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
            </li>
          </ul>
          <div class="clearfix"></div>
        </div>
        <div class="x_content">
          <ul class="list-unstyled timeline">
            <!-- INICIO BLOCO OCORRENCIA-->
            <?php
            include_once "../scripts/Conexao-class.php";
            $conect = new Conexao();
            $link = $conect->getLink();

            $sql = "
            SELECT DISTINCT
            OCOR_ID, OCOR_ID_CLIENTE, OCOR_DESC_RESUMIDA, OCOR_DESCRICAO, OCOR_DTINCLUSAO
            FROM
            ocorrencias
            INNER JOIN
            acessos
            ON
            acessos.ACE_ID_MODULOS = ocorrencias.OCOR_ID_MODULOS
            INNER JOIN
            usuario
            ON
            usuario.USER_ID = acessos.ACE_USUARIO_ID
            WHERE OCOR_DTPRAZO = 0
            AND
            ACE_BLOQUEADO != 1
            AND
            USER_ID = ".$_SESSION['userId']."
            AND OCOR_DTENCERRAMENTO = 0";
            $qtdeOcorrencias = mysqli_query($link, $sql);

            if (mysqli_num_rows($qtdeOcorrencias) == 0)
            {
              echo "<p align=center>
              Nenhuma ocorrência encontrada em nossa base para você
            </p>
            ";
          }

          while ($row = $qtdeOcorrencias->fetch_assoc())
          {
           echo "<li>
           <div class=\"block\">
            <div class=\"tags\">
              <a href=\"assentamento.php?idocorr=".$row['OCOR_ID']."\" class=\"tag\">";
                $sql = "SELECT CLI_NREDUZ FROM clientes WHERE CLI_ID=".$row['OCOR_ID_CLIENTE'];
                $result = mysqli_query($link, $sql);
                $cliente = $result->fetch_assoc();
                echo "<span>(".$row['OCOR_ID'].") ".$cliente['CLI_NREDUZ']."</span>
              </a>
            </div>
            <div class=\"block_content\">
              <h2 class=\"title\">
                <a>".$row['OCOR_DESC_RESUMIDA']."</a>
              </h2>
              <div class=\"byline\">
                <span>".$row['OCOR_DTINCLUSAO']."</span> por <a> ".$cliente['CLI_NREDUZ']."</a>
              </div>
              <p > ".$row['OCOR_DESCRICAO']."
              </p>
            </div>
          </div>
        </li>";
        mysqli_free_result($result);
      }
      ?>
      <!-- FIM BLOCO OCORRENCIA-->
    </ul>
  </div>
</div>
</div>

<div class="clearfix"></div>
</div>
<!-- FIM ROW -->

</div>
<!-- FOOTER -->
<footer>
  <div class="pull-right">
    Compacta Dashboard desenvolvida por <a href="http://www.solucaocompacta.com.br">Solução Compacta</a>
  </div>
  <div class="clearfix"></div>
</footer>
<!-- /FOOTER -->
<!-- FIM DE CONTEÚDO -->



<!-- INSERÇÃO NA AGENDA -->
<div id="CalenderModalNew" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myModalLabel">Adicionar novo evento</h4>
        <label id="lblCriador"> Por: - Em:  </label>
      </div>
      <div class="modal-body">
        <div id="testmodal" style="padding: 5px 20px;">
          <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="../scripts/cadEventos.php" method="post">
            <!-- Para mostrar o ticket se estiver definido-->
            <label hidden id="ticket" class="ticket"></label>
            <!-- Componente Cliente -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Cliente:</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select class="form-control" tabindex="-1" name="cliente" id="cliente" required>

                  <?php
                  include_once "../scripts/Conexao-class.php";
                  $conect = new Conexao();
                  $link = $conect->getLink();
                  $sql = "SELECT * FROM clientes WHERE CLI_BLOQUEADO != 1 ORDER BY CLI_NREDUZ";
                  $result = mysqli_query($link,$sql);
                  $i = 1;
                  while ($row = $result->fetch_assoc())
                  {
                    echo "<option value=\"".$row['CLI_ID']."\"> ".$row['CLI_NREDUZ']." </option>"
                    ;
                    $i++;
                  }
                  ?>
                </select>
              </div>
            </div>
            <!-- Componente  assunto -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Assunto</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <input type="text" class="form-control" id="assunto" name="assunto" maxLength="120">
              </div>
            </div>
            <!-- Componente  descriçao -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Descrição</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <textarea class="form-control" style="height:55px;" id="descricao" name="descricao"></textarea>
              </div>
            </div>
            <!-- Componente consultor -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Consultor:</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select class="form-control" tabindex="-1" name="consultor" id="consultor">
                  <option value=0 selected="true"></option>
                  <?php
                  include_once "../scripts/Conexao-class.php";
                  $conect = new Conexao();
                  $link = $conect->getLink();
                  $sql = "SELECT * FROM consultor WHERE CONS_TIPO != '' ORDER BY CONS_NOME";
                  $result = mysqli_query($link,$sql);
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

            <!-- Componente Módulo -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Módulo:</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select tabindex="-1" class="form-control col-md-9 col-xs-9 col-sm-9" name="modulo" id="modulo" value="0">
                  <?php
                  include_once "../scripts/Conexao-class.php";
                  $conect = new Conexao();
                  $link = $conect->getLink();

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
                  ORDER BY MOD_DESC_REDUZ
                  ";

                  $result = mysqli_query($link,$sql);
                  $i = 1;
                  while ($row = $result->fetch_assoc())
                  {
                    echo "<option value=".$row['MOD_ID']."> ".$row['MOD_DESC_REDUZ']." </option>"
                    ;
                    $i++;
                  }
                  ?>
                </select>
              </div>
            </div>

            <!-- Componente ocorrencia -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Ticket de ocorrência:</label>
              <div class="col-md-9 col-sm-9 col-xs-12">
                <select class="form-control" tabindex="-1" name="ocorrencia" id="ocorrencia">
                  <option value=0>...</option>
                  <?php
                  include_once "../scripts/Conexao-class.php";
                  $conect = new Conexao();
                  $link = $conect->getLink();
                  if (!isset($_SESSION)) session_start();
                  $sql = "
                  SELECT DISTINCT OCOR_DESC_RESUMIDA, OCOR_ID, OCOR_DTENCERRAMENTO
                  FROM ocorrencias
                  INNER JOIN ACESSOS
                  ON ACE_ID_MODULOS = OCOR_ID_MODULOS
                  INNER JOIN USUARIO
                  ON USER_ID = ACE_USUARIO_ID
                  WHERE
                  OCOR_DTENCERRAMENTO = 0
                  AND USER_ID = ".$_SESSION['userId']."
                  AND ACE_BLOQUEADO != 1
                  ";

                  $result = mysqli_query($link,$sql);
                  $i = 1;
                  while ($row = $result->fetch_assoc())
                  {
                    $modulo = str_replace("_", " ", $row['OCOR_DESC_RESUMIDA']);
                    echo "<option value=\"".$row['OCOR_ID']."\">(".$row['OCOR_ID'].")".$modulo."</option>"
                    ;
                    $i++;
                  }
                  ?>
                </select>
              </div>
            </div>

            <!-- Componente Data -->
            <div class="form-group">
              <label class="control-label col-md-3 col-sm-3 col-xs-12">Data<span class="required">*</span>
              </label>
              <div class="col-md-6 col-sm-6 col-xs-12">
                <input id="dataManual" name='dataManual' class="date-picker form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99/99/9999'" placeholder="12/12/2016" type="text">
              </div>
            </div>

            <input type="text" name="enderecoUrl" id="enderecoUrl" hidden>
            <input type="text" name="idUpdate" id="idUpdate" value=0 hidden>
            <input type="text" name="dataHidden" id="dataHidden" value=0 hidden>
            <input type="text" name="clienteHidden" id="clienteHidden" value=0 hidden>
            <input type="text" name="idCriador" id="idCriador" value=0 hidden>
            <input type="text" name="data" id="data" hidden>
            <button type="submit" class="btn btn-primary antosubmit">Salvar na agenda</button>
            <button type="button" id="excluir" class="btn btn-danger" hidden>Excluir</button>
            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Fechar</button>
            <div id='divConverterChamado' hidden>
              <button type="button" class="btn btn-info" id="btnConverterChamado">
                Converter em chamado </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="custom_notifications" class="custom-notifications dsp_none">
    <ul class="list-unstyled notifications clearfix" data-tabbed_notifications="notif-group">
    </ul>
    <div class="clearfix"></div>
    <div id="notif-group" class="tabbed_notifications"></div>
  </div>
  <!-- /INSERÇÃO NA AGENDA -->

  <!-- Dinaica do btn de converter chamado -->
  <script type="text/javascript">
    $(document).ready(function(){
      $('#btnConverterChamado').on('click', function(){
        var modulo = $('#modulo').val();
        var assunto   = $('#assunto').val();
        var descricao = $('#descricao').val();
        var cliente   = $('#clienteHidden').val();
        var consultor = $('#consultor').val();
        var prazo     = $('#dataHidden').val();
        var evento    = $('#idUpdate').val();

        var link = "ocorrencias.php?assunto="+assunto+"&descricao="+descricao
        +"&idCliente="+cliente+"&idConsultor="+consultor+"&prazo="+prazo+"&idEvento="+evento+"&idModulo="+modulo;

        window.location.replace(link);
      });
    })
  </script>

  <!-- AJAX EXCLUSÃO EVENTO -->
  <script type="text/javascript">
    $(document).ready(function(){
      $('#enderecoUrl').val($(location).attr('href'));

      $('#excluir').on('click', function(){
        var idEvento =  $('#idUpdate').val();
        var idCriador = $('#idCriador').val();
        $.ajax({
          type: 'POST',
          url: '../scripts/cadEventos.php',
          data: {
            idEventoExcluir: idEvento,
            idCriador: idCriador
          },
          success: function(result)
          {
            var endereco = $(location).attr('href');
            window.location.replace(endereco);
          },
          error: function(result)
          {
            alert(result);
          }
        });
      });
    });
  </script>
  <!--/AJAX EXCLUSÃO EVENTO -->

  <div id="fc_create" data-toggle="modal" data-target="#CalenderModalNew"></div>



  <!-- /calendar modal -->

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
  <!-- bootstrap-wysiwyg -->
  <script src="../vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
  <script src="../vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
  <script src="../vendors/google-code-prettify/src/prettify.js"></script>
  <!-- Parsley -->
  <script src="../vendors/parsleyjs/dist/parsley.js"></script>
  <!-- Autosize -->
  <script src="../vendors/autosize/dist/autosize.min.js"></script>
  <!-- jquery.inputmask -->
  <script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
  <!-- PNotify -->
  <script src="../vendors/pnotify/dist/pnotify.js"></script>
  <script src="../vendors/pnotify/dist/pnotify.buttons.js"></script>
  <script src="../vendors/pnotify/dist/pnotify.nonblock.js"></script>
  <!-- FullCalendar -->
  <script src="../vendors/moment/min/moment.min.js"></script>
  <script src="../vendors/fullcalendar/dist/fullcalendar.min.js"></script>
  <!-- Select2 -->
  <script src="../vendors/select2/dist/js/select2.full.min.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

  <!-- Custom Theme Scripts -->
  <script src="../build/js/custom.min.js"></script>

  <!-- modulos dinamicos ao atualizar consultor -->
  <script type="text/javascript">
    function atualizaModulo(idModulo = 0) {
      var idConsultor = $('#consultor').val();

      $.ajax({
        type: 'POST',
        url: 'combosModuloHome.php',
        data: {
          idConsultor: idConsultor
        },
        success: function(result){
          $('#modulo')
          .empty()
          .append(result);

          $('#modulo').val(idModulo);
          atualizarComboOcorrencia();
        },
        error: function(){
          alert('Erro ao requisitar valores');
        }
      });
    }


  </script>
  <!-- aviso atrasos -->
  <script type="text/javascript">
    $(document).ready(function() {
      var eventoAtrasado = "<?php echo $temEventoAtrasado?>";
      var chamadoAtrasado = "<?php echo $temChamadoAtrasado?>";
      var osAtrasada = "<?php echo $temOSAtrasada?>";
      var mensagemAtraso = "<?php echo $mensagemAtraso?>";

      if (eventoAtrasado || chamadoAtrasado || osAtrasada){
        new PNotify({
          title: "Atrasos pendentes",
          type: "error",
          text: mensagemAtraso,
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
      }
    });
  </script>

  <!-- bootstrap-daterangepicker -->
  <script>
    $(document).ready(function() {
      $('#dataManual').daterangepicker({
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



  <!-- ocorrencias relacionadas a clientes -->
  <script type="text/javascript">
    $('#cliente').on('change', function(){
      atualizarComboOcorrencia();
    });

    $('#consultor').on('change', function(){
      atualizaModulo();
      atualizarComboOcorrencia();
    });

    $('#modulo').on('change', function(){
      atualizarComboOcorrencia();
    });

    function atualizarComboOcorrencia()
    {
      var consultor = $('#consultor').val();
      var modulo    = $('#modulo').val();

      if($('#cliente').val()) {
        var cliente = $('#cliente').val();

        $.ajax({
          type: 'POST',
          url: 'comboHome.php',
          data: {
            idCliente: cliente,
            consultor: consultor,
            modulo: modulo
          },
          success: function(result){
            $('#ocorrencia')
            .empty()
            .append(result)
            .attr('disabled',false);


          },
          error: function(){
            alert('Erro ao requisitar valores');
          }
        });
      }
    }
    $(document).ready(function (){
      $('#cliente').on('change', function()
      {
        atualizarComboOcorrencia();
      });
    });
  </script>

  <!-- FullCalendar -->
  <script>

    function saveEvent ()
    {
      var cliente = $('#clienteHidden').val();
      var consultor = $('#consultor').val();
      var assunto = $('#assunto').val();
      var descricao = $('#descricao').val();
      var data = $('#dataHidden').val();
      var ocorrencia = $('#ocorrencia').val();
      var idUpdate = $('#idUpdate').val();
      if (cliente == "" || assunto == "")
        alert('Preencha corretamente os campos cliente e assunto!');

    }

    /*recebe data xx/xx/xxxx*/
    function ehAtraso (data) {
      var d = new Date();
      var dataArray = data.split("/");
      var diaEvento = dataArray[0];
      var mesEvento = dataArray[1];
      var anoEvento = dataArray[2];
      var diaAtual = d.getDate();
      var mesAtual = d.getMonth();
      var anoAtual = d.getFullYear();
      mesAtual+=1;

      if (anoEvento < anoAtual) return true;
      if (anoEvento >= anoAtual)
        if (mesEvento <= mesAtual)
          if (mesEvento < mesAtual) return true;
        else if (mesEvento == mesAtual)
          if (diaEvento < diaAtual) return true;
        return false;
      }

      $(document).ready(function(){

        $('#cliente').on('change', function() {
          $('#clienteHidden').val($('#cliente').val());
        });

        // forca a troca do clienteHidden
        $('#cliente').change();
      });
      $(window).load(function() {
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
        var temEventoAtrasado = "<?php echo $temEventoAtrasado?>";
        var temChamadoAtrasado = "<?php echo $temChamadoAtrasado?>";
        var temOSAtrasada = "<?php echo $temOSAtrasada?>";

        if (temEventoAtrasado) {
          alert('Você possui eventos atrasados, atualize a data dos mesmos convertendo-os em chamados ou relacione-os a ocorrências para poder criar e editar os eventos atuais.');
          return;
        }

        if (temChamadoAtrasado) {
          alert('Elimine as pendencias em: chamados, para inserir itens da agenda.');
          return;
        }

        if (temOSAtrasada) {
          alert('Elimine as pendencias em: ordem de serviço, para inserir itens da agenda.');
          return;
        }

        $('#divConverterChamado').css('display', 'none');
        var date1 = new Date();
        var dia = date1.getDate();
        var mes = date1.getMonth()+1;
        var ano = date1.getFullYear();

        var dataAtual = start.format();
        var dataAtual = dataAtual.split("-");

        var diaAgenda = parseInt(dataAtual[2]);
        var mesAgenda = parseInt(dataAtual[1]);
        var anoAgenda = parseInt(dataAtual[0]);

        var atrasado = false;
        if (anoAgenda < ano)
          atrasado = true;

        if (anoAgenda <= ano)
          if (mesAgenda <= mes)
            if (mesAgenda < mes)
              atrasado = true;
            if (mesAgenda == mes && diaAgenda < dia)
              atrasado = true;

            if (atrasado)
            {
              alert('Não se podem cadastrar eventos em datas já passadas!');

              //deseleciona o dia
              calendar.fullCalendar('unselect');

              //fecha o modal
              $('.antoclose').click();
            }
        //exibe o modal de inserção
        $('#cliente').attr('disabled',false);
        $('#dataManual').attr('disabled',false);
        $('#fc_create').click();
        $('#excluir').css('display','none');
        $('#lblCriador').attr('hidden', true);

        var clienteSession = "<?php echo $_SESSION['filtroCliente']?>";
        var consultorSession = "<?php echo $_SESSION['filtroConsultor']?>";

        if(consultorSession != "0"){
          $('#consultor').val(consultorSession);
        }

        if (clienteSession != "0"){
          $('#clienteHidden').val(clienteSession);
          $('#cliente').val(clienteSession).change();
        }


        atualizarComboOcorrencia();
        atualizaModulo();

        $('#idUpdate').val('0');
        $('#idCriador').val('0');
        $('#ocorrencia').val('0');
        $('#ocorrencia').attr('disabled',true);
        $('#dataManual').val(start.format("DD/MM/YYYY"));
        $('#dataHidden').val(start.format("DD/MM/YYYY"));
        //pega o moment para inserir o evento na agenda
        started = start;
        ended = end;

        //se clicou em submit para inserir
        $(".antosubmit").on("click", function()
        {
          //pega o titulo
          var title = $("#title").val();
          //verifica se tem data de fim
          if (end) {
            ended = end;
          }

          categoryClass = $("#event_type").val();

          //se houver titulo renderiza o evento na agenda
          if (title) {
            calendar.fullCalendar('renderEvent', {
              title: title,
              start: started,
              end: end
            },
                  true // make the event "stick"
                  );
          }

          //reseta os campos do formulário
          $('#title').val('');

          //deseleciona o dia
          calendar.fullCalendar('unselect');

          //fecha o modal
          $('.antoclose').click();

          return false;
        });
      },
      eventMouseover: function(data, jsEvent,view){
        var descricao = data.descricao;
        while (descricao.indexOf("&#13") > 0)
        {
          descricao = descricao.replace("&#13;","<br>");
        }

        tooltip = '<div class="tooltiptopicevent" style="width:auto;height:auto;background:rgba(255,255,255,0.8);position:absolute;z-index:10001;padding:10px 10px 10px 10px ;  line-height: 200%;"><strong>'+data.assunto+'</strong><br>'+descricao+'</div>';


        if (data.tipo == 'assentamento') {
          tooltip = '<div class="tooltiptopicevent" style="width:auto;height:auto;background:rgba(255,255,255,0.8);position:absolute;z-index:10001;padding:10px 10px 10px 10px ;  line-height: 200%;"><strong>'+data.assunto+'</strong>  <br>Prioridade: '+data.prioridade+'<br>'+'Prazo limite: '+data.prazo+'<br/>'+'Classificacao: '+data.classificacao+'<br/>'+descricao+'</div>';
        }



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
        $('.tooltiptopicevent').remove();

        var temEventoAtrasado = "<?php echo $temEventoAtrasado?>";
        var temChamadoAtrasado = "<?php echo $temChamadoAtrasado ?>";
        var temOSAtrasada = "<?php echo $temOSAtrasada ?>";

        var temChamadoAtrasado = "<?php echo $temChamadoAtrasado ?>";
        var dataEvento = calEvent.start.format("DD/MM/YYYY");
        if (!ehAtraso(dataEvento) && temEventoAtrasado == 1 || (!ehAtraso(dataEvento) &&temChamadoAtrasado)) {
          alert('Você possui eventos atrasados, atualize a data dos mesmos convertendo-os em chamados ou relacione-os a ocorrências para poder criar e editar os eventos atuais.');
          return;
        }

        if (!ehAtraso(dataEvento) && temOSAtrasada) {
          alert('Elimine suas pendências para criar novos itens na agenda.');
          return;
        }

        if (calEvent.tipo == 'assentamento') {
          if (calEvent.urli) {
            window.open(calEvent.urli, "_blank");
            return false;
          }
        }
        else {

          // TRECHO DE CORRECAO INICIO
          $('#fc_create').click(); // chama o modal

          var dataInclusao = calEvent.dataCriacao;
          var criador      = calEvent.nomeCriador;
          var idCriador    = calEvent.criador;
          var cliente = calEvent.cliente;

          $('#cliente').attr('disabled',true);
          $('#ocorrencia').attr('disabled',false);
          $('#dataManual').attr('disabled',true);

          // usuario e nivel de usuario logado
          var usuario = "<?php echo $_SESSION['userId'] ?>";
          var nivel   = "<?php echo $_SESSION['userNivel']?>";

          // checa se pode trocar consultor ou exibir botao excluir
          if (usuario == idCriador || nivel == 'ADMINISTRADOR' || usuario == calEvent.consultor) {
            $('#excluir').css('display','');
            $('#consultor').attr('disabled',false);
          } else {
            $('#excluir').css('display','none');
            $('#consultor').attr('disabled',true);
          }

          //para aparecer o btnConverter chamado
          if (usuario == calEvent.consultor || nivel == 'ADMINISTRADOR')
            $('#divConverterChamado').css('display', 'block');
          else
            $('#divConverterChamado').css('display', 'none');

          var textoLabelEvent = "Criado por: "+criador+" - Em: "
                              +  dataInclusao;

          $('#lblCriador').html(textoLabelEvent);
          $('#lblCriador').attr('hidden', false);
          $('#cliente').val(calEvent.cliente);

          $.ajax({
            type: 'POST',
            url: 'comboHome.php',
            data: {
              idCliente: cliente
            },
            success: function(result){
              $('#ocorrencia')
              .empty()
              .append(result)
              .attr('disabled',false);
            },
            error: function(){
              alert('Erro ao requisitar valores');
            }
          });

          $('#clienteHidden').val(calEvent.cliente);
          $('#assunto').val(calEvent.assunto);
          $('#descricao').html(calEvent.descricao);
          $('#consultor').val(calEvent.consultor);
          atualizaModulo(calEvent.modulo);
          $('#idUpdate').val(calEvent.id);
          $('#idCriador').val(calEvent.criador);
          $('#dataManual').val(calEvent.start.format("DD/MM/YYYY"));
          $('#dataHidden').val(calEvent.start.format("DD/MM/YYYY"));

          categoryClass = $("#event_type").val();

          $(".antosubmit").on("click", function() {
            calEvent.title = $("#title2").val();

            calendar.fullCalendar('updateEvent', calEvent);
            $('.antoclose').click();

          });

          calendar.fullCalendar('unselect');
          // TRECHO DE CORRECAO FIM
        }
      },
      eventOrder: ["tipo", "prazoLimite", "-prioridade", "id"],
      //eventDrop para alterar a data de um evento
      eventDrop: function(event, delta, revertFunc) {

        //alert(event.title + " was dropped on " + event.start.format());

        if (confirm("Você realmente quer mudar a data deste evento?")) {

        }

      },
      //Eventos já cadastrados no banco de dados serão exibidos aqui:
      events: [
      <?php
      if (!isset($_SESSION)) session_start();
      include_once "../scripts/Conexao-class.php";
      include_once "../scripts/ConversorData-class.php";
      include_once "AnalisaData.class.php";
      $conversor = new ConversorData;
      $conect = new Conexao();
      $link = $conect->getLink();
      $analise = new AnalisaData;

      // selecionar eventos

      $sql = "SELECT DISTINCT EVENTO_ASSUNTO,EVENTO_DESCRICAO,EVENTO_CONSULTOR_ID,EVENTO_CRIADOR,EVENTO_CLIENTE_ID,EVENTO_ID,EVENTO_DATA, EVENTO_MODULO, EVENTO_DTINCLUSAO
      FROM EVENTOS
      INNER JOIN ACESSOS
      ON ACE_ID_MODULOS = EVENTO_MODULO
      WHERE EVENTO_OCORRENCIA < 1
      AND ACE_USUARIO_ID =".$_SESSION['userId']."
      AND ACE_BLOQUEADO != 1
      AND EVENTO_CONSULTOR_ID=".$_SESSION['userId'];

      if ($_SESSION['userNivel'] == 'ADMINISTRADOR')
        $sql = "SELECT * FROM eventos WHERE EVENTO_OCORRENCIA < 1";


        if ($mostrarAtrasados) {
        # query para buscar somente chamados atrasados
          $sql = "SELECT DISTINCT EVENTO_ASSUNTO,EVENTO_DESCRICAO,EVENTO_CONSULTOR_ID,EVENTO_CRIADOR,EVENTO_CLIENTE_ID,EVENTO_ID,EVENTO_DATA, EVENTO_MODULO, EVENTO_DTINCLUSAO
            FROM EVENTOS
            INNER JOIN ACESSOS
            ON ACE_ID_MODULOS = EVENTO_MODULO
            WHERE EVENTO_OCORRENCIA < 1
            AND ACE_USUARIO_ID =".$_SESSION['userId']."
            AND ACE_BLOQUEADO != 1
            AND EVENTO_CONSULTOR_ID=".$_SESSION['userId']."
            AND EVENTO_DATA < '".date("Y-m-d")."'";
      }

      //filtra agenda pelo get
      if ((isset($_GET['filtroConsultor']) && $_GET['filtroConsultor'] != "")
        || (isset($_GET['filtroCliente'])  && $_GET['filtroCliente'] != "")
        || ($_SESSION['filtroConsultor'] != "0" || $_SESSION['filtroCliente'] != "0"))
      {
        $consultor = $_SESSION['filtroConsultor'];
        $cliente   = $_SESSION['filtroCliente'];
        $sql = "
        SELECT DISTINCT EVENTO_ASSUNTO,EVENTO_DESCRICAO,EVENTO_CONSULTOR_ID,EVENTO_CRIADOR,EVENTO_CLIENTE_ID,EVENTO_ID,EVENTO_DATA, EVENTO_MODULO, EVENTO_DTINCLUSAO
        FROM EVENTOS
        INNER JOIN ACESSOS
        ON ACE_ID_MODULOS = EVENTO_MODULO
        WHERE EVENTO_OCORRENCIA < 1
        AND ACE_USUARIO_ID =".$_SESSION['userId']."
        AND ACE_BLOQUEADO != 1
        ";

        if ($mostrarAtrasados) {
        # query para buscar somente chamados atrasados
          $sql = "SELECT DISTINCT EVENTO_ASSUNTO,EVENTO_DESCRICAO,EVENTO_CONSULTOR_ID,EVENTO_CRIADOR,EVENTO_CLIENTE_ID,EVENTO_ID,EVENTO_DATA, EVENTO_MODULO, EVENTO_DTINCLUSAO
            FROM EVENTOS
            INNER JOIN ACESSOS
            ON ACE_ID_MODULOS = EVENTO_MODULO
            WHERE EVENTO_OCORRENCIA < 1
            AND ACE_USUARIO_ID =".$_SESSION['userId']."
            AND ACE_BLOQUEADO != 1
            AND EVENTO_CONSULTOR_ID=".$_SESSION['userId']."
            AND EVENTO_DATA < '".date("Y-m-d")."'";
      }

        if (isset($_GET['filtroConsultor']) && $_GET['filtroConsultor'] != "")
          $consultor = $_GET['filtroConsultor'];

        if (isset($_GET['filtroCliente']) && $_GET['filtroCliente'] != "")
          $cliente = $_GET['filtroCliente'];

        if ($consultor != 0 && $consultor != "")
          $sql .= " AND EVENTO_CONSULTOR_ID=".$consultor;

        if ($cliente != 0 && $cliente != "")
          $sql .= " AND EVENTO_CLIENTE_ID=".$cliente;

      }

      $result = mysqli_query($link, $sql);

      if (!mysqli_query($link, $sql))
      {
        echo mysqli_error($link);
        exit;
      }

      $eventos = "";

      $red = "#f44";
      $orange = "#f73";
      $green = "#8f9";
      $blue = "#89f";
      $evento = '#acf';
      $chamado = '#3af';

      while ($row = $result->fetch_assoc())
      {
        //PARA PRAZOS OU PRIORIDADES MUDAR A COR
        $color = $evento;

        $dataEvento = $conversor->sql2Brasil($row['EVENTO_DATA']);

        /*checa atrasos*/
        $ehAtrasado = $analise->ehAtrasado($dataEvento);
        if($ehAtrasado)
          $color = $red;

        //PARA PRAZOS OU PRIORIDADES MUDAR A COR

        $sqlAux = "SELECT USER_NOME FROM usuario WHERE USER_ID=".$row['EVENTO_CRIADOR'];
        $resultAux = mysqli_query($link, $sqlAux);
        $nomeCriador = $resultAux->fetch_assoc();
        $nomeCriador = $nomeCriador['USER_NOME'];

        $nomeCliente = $row['EVENTO_CLIENTE_ID'];
        $sql = "SELECT CLI_NREDUZ FROM CLIENTES WHERE CLI_ID=".$nomeCliente." LIMIT 1";
        $resultAux = mysqli_query($link, $sql);
        $nomeCliente = $resultAux->fetch_assoc();
        $nomeCliente = $nomeCliente['CLI_NREDUZ'];

        $nomeConsultor = $row['EVENTO_CONSULTOR_ID'];
        $sql = "SELECT USER_LOGIN FROM USUARIO WHERE USER_ID=".$nomeConsultor." LIMIT 1";
        $resultAux = mysqli_query($link, $sql);
        $nomeConsultor = $resultAux->fetch_assoc();
        $nomeConsultor = $nomeConsultor['USER_LOGIN'];
        $nomeConsultor = substr($nomeConsultor, 0, strpos($nomeConsultor, "@"));
        $descricao = $row['EVENTO_DESCRICAO'];
        $descricao = str_replace("'", "\'", $descricao);

        $assunto = $row['EVENTO_ASSUNTO'];
        $assunto = str_replace("'","\'",$assunto);

        $titulo = $nomeConsultor."-".$nomeCliente;
        $eventos .= "
        {
          title: '".$titulo."',
          start: '".$conversor->sql2Agenda($row['EVENTO_DATA'])."',
          id: ".$row['EVENTO_ID'].",
          assunto: '".$assunto."',
          color: '".$color."',
          descricao: '".$descricao."',
          consultor: ".$row['EVENTO_CONSULTOR_ID'].",
          cliente: ".$row['EVENTO_CLIENTE_ID'].",
          criador: '".$row['EVENTO_CRIADOR']."',
          dataCriacao: '".$row['EVENTO_DTINCLUSAO']."',
          nomeCriador: '".$nomeCriador."',
          modulo: '".$row['EVENTO_MODULO']."',
          tipo: '0',
          prioridade: 0,
          prazoLimite: 0,
          classificacao: '(evento)'
        },";
      }
      // fim selecionar eventos
      // selecionar chamados

      $sql = "SELECT DISTINCT
      OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_DTPRAZO, OCOR_CONSULTOR, OCOR_ID_CLIENTE,OCOR_DESCRICAO, OCOR_IMPACTO, OCOR_DTLIMITE, OCOR_CLASSIFICACAO
      FROM
      ocorrencias
      INNER JOIN acessos
      ON acessos.ACE_ID_MODULOS = ocorrencias.OCOR_ID_MODULOS
      INNER JOIN usuario
      ON usuario.USER_ID = acessos.ACE_USUARIO_ID
      WHERE
      usuario.USER_ID = ".$_SESSION['userId']."
      AND
      ocorrencias.OCOR_DTENCERRAMENTO = 0
      AND
      ocorrencias.OCOR_CONSULTOR = ".$_SESSION['userId']."
      AND
      ACE_BLOQUEADO != 1";

      if ($_SESSION['userNivel'] == 'ADMINISTRADOR')
        $sql = "SELECT * FROM OCORRENCIAS WHERE OCOR_DTENCERRAMENTO = 0";

        if ($mostrarAtrasados) {
        # query para buscar somente chamados atrasados
           $sql = "SELECT DISTINCT
                  OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_DTPRAZO, OCOR_CONSULTOR, OCOR_ID_CLIENTE,OCOR_DESCRICAO, OCOR_IMPACTO, OCOR_DTLIMITE, OCOR_CLASSIFICACAO
                  FROM
                  ocorrencias
                  INNER JOIN acessos
                  ON acessos.ACE_ID_MODULOS = ocorrencias.OCOR_ID_MODULOS
                  INNER JOIN usuario
                  ON usuario.USER_ID = acessos.ACE_USUARIO_ID
                  WHERE usuario.USER_ID = ".$_SESSION['userId']."
                  AND ocorrencias.OCOR_DTENCERRAMENTO = 0
                  AND ocorrencias.OCOR_CONSULTOR = ".$_SESSION['userId']."
                  AND ACE_BLOQUEADO != 1
                  AND OCOR_DTPRAZO < DATE(NOW())";
      }

       //filtra agenda pelo get
      if ((isset($_GET['filtroConsultor']) && $_GET['filtroConsultor'] != "")
        || (isset($_GET['filtroCliente'])  && $_GET['filtroCliente'] != "")
        || ($_SESSION['filtroConsultor'] != "0" || $_SESSION['filtroCliente'] != "0"))
      {
        $consultor = $_SESSION['filtroConsultor'];
        $cliente   = $_SESSION['filtroCliente'];

        $sql = "SELECT DISTINCT
        OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_DTPRAZO, OCOR_CONSULTOR, OCOR_ID_CLIENTE,OCOR_DESCRICAO, OCOR_IMPACTO, OCOR_DTLIMITE, OCOR_CLASSIFICACAO
        FROM
        ocorrencias
        INNER JOIN acessos
        ON acessos.ACE_ID_MODULOS = ocorrencias.OCOR_ID_MODULOS
        INNER JOIN usuario
        ON usuario.USER_ID = acessos.ACE_USUARIO_ID
        WHERE
        usuario.USER_ID = ".$_SESSION['userId']."
        AND
        ocorrencias.OCOR_DTENCERRAMENTO = 0
        AND ACE_BLOQUEADO != 1";

        if ($mostrarAtrasados) {
        # query para buscar somente chamados atrasados
           $sql = "SELECT DISTINCT
                  OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_DTPRAZO, OCOR_CONSULTOR, OCOR_ID_CLIENTE,OCOR_DESCRICAO, OCOR_IMPACTO, OCOR_DTLIMITE, OCOR_CLASSIFICACAO
                  FROM
                  ocorrencias
                  INNER JOIN acessos
                  ON acessos.ACE_ID_MODULOS = ocorrencias.OCOR_ID_MODULOS
                  INNER JOIN usuario
                  ON usuario.USER_ID = acessos.ACE_USUARIO_ID
                  WHERE usuario.USER_ID = ".$_SESSION['userId']."
                  AND ocorrencias.OCOR_DTENCERRAMENTO = 0
                  AND ocorrencias.OCOR_CONSULTOR = ".$_SESSION['userId']."
                  AND ACE_BLOQUEADO != 1
                  AND OCOR_DTPRAZO < DATE(NOW())";
      }

        if (isset($_GET['filtroConsultor']) && $_GET['filtroConsultor'] != "")
          $consultor = $_GET['filtroConsultor'];

        if (isset($_GET['filtroCliente']) && $_GET['filtroCliente'] != "")
          $cliente = $_GET['filtroCliente'];

        if ($consultor != 0 && $consultor != "")
          $sql .= " AND ocorrencias.OCOR_CONSULTOR=".$consultor;

        if ($cliente != 0 && $cliente != "")
          $sql .= " AND ocorrencias.OCOR_ID_CLIENTE=".$cliente;
      }

      // echo $sql;

      $result = mysqli_query($link, $sql);
      while ($row = $result->fetch_assoc())
      {
      //PARA PRAZOS OU PRIORIDADES MUDAR A COR
        $color = $chamado;
        $classificacao = $row['OCOR_CLASSIFICACAO'];

        if ($classificacao == 1) {
          $color = $green;
          $classificacao = '(helpdesk)';
        }
        if ($classificacao == 2) {
          $color = $blue;
          $classificacao = '(implantação)';
        }
        if ($classificacao == 3) {
          $color = '#000';
          $classificacao = '(desenvolvimento)';
        }

        $dataEvento = $conversor->sql2Brasil($row['OCOR_DTPRAZO']);


        /*checa atrasos */
        $ehAtrasado = $analise->ehAtrasado($dataEvento);
        if($ehAtrasado)
          $color = $red;


        //PARA PRAZOS OU PRIORIDADES MUDAR A COR

        $nomeCliente = $row['OCOR_ID_CLIENTE'];
        $sql = "SELECT CLI_NREDUZ FROM CLIENTES WHERE CLI_ID=".$nomeCliente." LIMIT 1";
        $resultAux = mysqli_query($link, $sql);
        $nomeCliente = $resultAux->fetch_assoc();
        $nomeCliente = $nomeCliente['CLI_NREDUZ'];

        $nomeConsultor = $row['OCOR_CONSULTOR'];
        $sql = "SELECT USER_LOGIN FROM USUARIO WHERE USER_ID=".$nomeConsultor." LIMIT 1";
        $resultAux = mysqli_query($link, $sql);
        if ($resultAux)
        {
          $nomeConsultor = $resultAux->fetch_assoc();
          $nomeConsultor = $nomeConsultor['USER_LOGIN'];
          $nomeConsultor = substr($nomeConsultor, 0, strpos($nomeConsultor, "@"));
        }else $nomeConsultor = "SEM CONSULTOR";

        $prazoLimite = ($row['OCOR_DTLIMITE'] === '0000-00-00') ? '0' : '1';
        $prazo = ($row['OCOR_DTLIMITE'] === '0000-00-00') ? '0' : '1';


        if ($row['OCOR_DTLIMITE'] === '0000-00-00') {
          $prazoLimite = '9999-99-99';
          $prazo = 'SEM PRAZO LIMITE';
        } else {
          $prazoLimite = $row['OCOR_DTLIMITE'];
          $prazo = $conversor->sql2Brasil($row['OCOR_DTLIMITE']);
        }

        $eventos .= "
        {
          title: '(".$row['OCOR_ID'].") - ".$nomeConsultor." - ".$nomeCliente."',
          start: '".$conversor->sql2Agenda($row['OCOR_DTPRAZO'])."',
          id: ".$row['OCOR_ID'].",
          assunto: '".$row['OCOR_DESC_RESUMIDA']."',
          descricao: '".str_replace("'","\'",$row['OCOR_DESCRICAO'])."',
          urli: 'assentamento.php?idocorr=".$row['OCOR_ID']."',
          color: '".$color."',
          tipo: 'assentamento',
          prioridade: '".$row['OCOR_IMPACTO']."',
          prazoLimite: '".$prazoLimite."',
          prazo: '".$prazo."',
          classificacao: '".$classificacao."'
        },";
      }
      // fim selecionar chamados
      $eventos = substr($eventos, 0, strlen($eventos) - 1);
      echo $eventos;

      ?>
      ]
    });
});
</script>
<!-- /FullCalendar -->

<script type="text/javascript">
  $('#fc_create').on('click', function(){
    atualizarComboOcorrencia();
    atualizaModulo();
  })
</script>


<script type="text/javascript">
  $(document).ready(function () {
    <?php
    if(isset($_GET['filtroCliente']) || isset($_GET['filtroConsultor'])){
      if ($_GET['filtroCliente'] == "")
       echo "
     $('#filtroConsultor').val('".$_GET['filtroConsultor']."').change();
     ";
     else if ($_GET['filtroConsultor'] == "")
       echo "
     $('#filtroCliente').val('".$_GET['filtroCliente']."').change();
     ";
     else
       echo "
     $('#filtroConsultor').val('".$_GET['filtroConsultor']."');
     $('#filtroCliente').val('".$_GET['filtroCliente']."');
     ";
   }
   ?>

 });

</script>

</body>
</html>
