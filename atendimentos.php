<?php
if(!isset($_SESSION)) session_start();
if ($_SESSION['userNivel'] != 'ADMINISTRADOR')
{
  echo "<script> window.location.replace('home.php'); </script>";
  exit;
}
?>
<!-- LOG ACESSOS -->
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
           <h3>Log de atendimentos</h3>

         </div>
       </div>
       <!-- ROW CADASTROS -->
       <div class="row">
        <div class="col-md-12 col-xs-12">


         <!-- TABELA DE CONSULTAS DE USUÁRIOS-->
         <div class="x_panel">
           <div class="x_title">
            <form>
              <div class="form-group" hidden>
                <?php
                if($_SESSION['userNivel'] == 'ADMINISTRADOR')
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
                      $sql = "SELECT * FROM CONSULTOR where CONS_BLOQUEADO != 1";
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
                      $sql = "SELECT * FROM clientes WHERE CLI_BLOQUEADO != 1";
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
                    <a href="atendimentos.php" class="btn btn-info">Limpar filtro</a>   
                  </div>
                </div>
              </form>
            </div>
            <div class="x_content">
             <div class="clearfix"></div>
             <!-- consulta usuarios cadastrados no banco-->
             <?php
             include_once "../scripts/Conexao-class.php";
             include_once "../scripts/ConversorData-class.php";
             $conversor = new ConversorData;
             $conect = new Conexao();
             $db = $conect->getLink();

             $sql = 'SELECT * FROM `eventos` WHERE EVENTO_ID > 0 ';

             if (isset($_GET['filtroConsultor']) || ($_SESSION['filtroCliente'] != 0 || $_SESSION['filtroConsultor'] != 0))
             {

              //define o consultor
              $consultor = $_SESSION['filtroConsultor'];
              if (isset($_GET['filtroConsultor']) && $_GET['filtroConsultor'] != "")
                $consultor = $_GET['filtroConsultor'];

              //define o cliente
              $cliente = $_SESSION['filtroCliente'];
              if (isset($_GET['filtroCliente']) && $_GET['filtroCliente'] != "")
                $cliente = $_GET['filtroCliente'];
              

              if ($cliente != 0 && $cliente != "")
                $sql .= " AND EVENTO_CLIENTE_ID=".$cliente;
              if ($consultor != 0 && $consultor != "")
                $sql .= " AND EVENTO_CONSULTOR_ID=".$consultor;

            }


            $result = $db->query($sql);

            echo "<table id=\"datatable-responsive\" class=\"table table-striped table-bordered dt-responsive nowrap\" cellspacing=\"0\" width=\"100%\">
            <thead>
             <tr>
               <th>USUARIO</th>
               <th>EVENTO</th>
               <th>CLIENTE</th>
               <th>OCORRÊNCIA</th>
               <th>DATA</th>
               <th>EDITAR</th>
             </tr>
           </thead>
           <tbody>";
            if($result){
             while ($row = $result->fetch_assoc()){
              $sql = "SELECT USER_NOME FROM usuario WHERE USER_ID='".$row['EVENTO_CONSULTOR_ID']."'";
              $resultado = mysqli_query($db, $sql);
              $dadosUsuario = $resultado->fetch_assoc();

              $sql = "SELECT CLI_NOME FROM CLIENTES WHERE CLI_ID=".$row['EVENTO_CLIENTE_ID'];
              $cliNome = mysqli_query($db, $sql);
              $cliNome = $cliNome->fetch_assoc();
              $cliNome = $cliNome['CLI_NOME'];

              //busca descricao resumida da ocorrencia
              if ($row['EVENTO_OCORRENCIA'] < 1)
              {
                $ocorrencia = "NÃO";
              }
              else
              {
                $sql = "SELECT OCOR_ID, OCOR_DESC_RESUMIDA FROM OCORRENCIAS WHERE OCOR_ID=".$row['EVENTO_OCORRENCIA'];

                $ocorrencia = mysqli_query($db, $sql);
                $ocorrencia = $ocorrencia->fetch_assoc();
                $ocorrencia = "(".$ocorrencia['OCOR_ID'].") ".$ocorrencia['OCOR_DESC_RESUMIDA']."";
              }

              echo "<tr>";
              echo "<td>" . $dadosUsuario['USER_NOME'] . "</td>";
              echo "<td>" . $row['EVENTO_ASSUNTO'] . "</td>";
              echo "<td>" . $cliNome . "</td>";
              echo "<td> ". $ocorrencia."</td>";
              echo "<td>" . $conversor->sql2Brasil($row['EVENTO_DATA']) . "</td>";
              echo "<td> <button class='btn btn-sm btn-primary' onclick='showModal(".$row['EVENTO_ID'].")'>EDITAR </button> </td>";
              echo "</tr>";
            }
            $result->free();
          }
          echo    "</tbody>
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
  <!-- FIM ROW -->
</div>
</div>
</div>
<!-- FIM DA PÁGINA DE CONTEÚDO -->
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

<!-- INSERÇÃO NA AGENDA -->
<div id="CalenderModalNew" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title" id="myModalLabel">Adicionar novo evento</h4>
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
                  <option></option>
                  <?php
                  include_once "../scripts/Conexao-class.php";
                  $conect = new Conexao();
                  $link = $conect->getLink();
                  $sql = "SELECT * FROM clientes WHERE CLI_BLOQUEADO != 1";
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
                  $sql = "SELECT * FROM consultor";
                  $result = mysqli_query($link,$sql);
                  while ($row = $result->fetch_assoc())
                  { 
                    $modulo = str_replace("_", " ", $row['CONS_NOME']);
                    echo "<option value=\"".$row['CONS_ID']."\"> ".$modulo." </option>"
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
                  <option value=0></option>
                  <?php
                  include_once "../scripts/Conexao-class.php";
                  $conect = new Conexao();
                  $link = $conect->getLink();
                  if (!isset($_SESSION)) session_start();
                  $sql = "
                  SELECT OCOR_DESC_RESUMIDA, OCOR_ID,OCOR_DTENCERRAMENTO
                  FROM
                  ocorrencias
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
            
            <input type="text" name="ehLog" val="true" hidden>
            <input type="text" name="enderecoUrl" id="enderecoUrl" hidden>
            <input type="text" name="idUpdate" id="idUpdate" value=0 hidden>
            <input type="text" name="dataHidden" id="dataHidden" value=0 hidden>
            <input type="text" name="clienteHidden" id="clienteHidden" value=0 hidden>
            <input type="text" name="data" id="data" hidden>
            <button type="submit" class="btn btn-primary antosubmit">Salvar LOG</button>
            <button type="button" class="btn btn-default antoclose" data-dismiss="modal">Fechar</button>  
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- /INSERÇÃO NA AGENDA -->


<div id="fc_create" data-toggle="modal" data-target="#CalenderModalNew"></div>

<!-- Show Modal-->
<script type="text/javascript">
  function showModal(id)
  {
    if (id > 0)
    {
      $.ajax({
        type: 'POST',
        url: 'dadosEvento.php',
        data: {
          idEvento: id
        },
        success: function(result){

          var retorno = result;
          var valores = retorno.split("|");
          
          $('#idUpdate').val(id);
          $('#cliente').val(valores[0]);
          $('#cliente').attr('disabled',true);
          $('#clienteHidden').val(valores[0]);
          $('#assunto').val(valores[1]);
          $('#descricao').val(valores[2]);
          $('#consultor').val(valores[3]);
          $('#ocorrencia').val(valores[4]);
          $('#dataManual').val(valores[5]);
          $('#dataManual').attr('disabled', true);
          $('#dataHidden').val(valores[5]);
        },
        error: function (result){
          alert(result);
        }
      });

      $('#fc_create').click();
    }
  }

  $(document).ready(function(){
    $('#enderecoUrl').val($(location).attr('href'));    
  });
</script>

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

<!-- Custom Theme Scripts -->
<script src="../build/js/custom.min.js"></script>

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
          'ordering': true
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
</body>
</html>
<?php
echo "<script>";
if (isset($_GET['filtroConsultor']))
{
  if ($_GET['filtroConsultor'] != "")
    echo "$('#filtroConsultor').val(".$_GET['filtroConsultor'].");";

  if ($_GET['filtroCliente'] != "")
    echo "$('#filtroCliente').val(".$_GET['filtroCliente'].");";
}
echo "</script>";
?>