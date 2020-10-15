
<!-- CADASTRO DE MÓDULOS -->
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
           <h3>Cadastro de feriados</h3>
         </div>
       </div> 

       <!-- ROW CADASTROS -->
       <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
         <!-- PAINEL DE CADASTRO -->
         <div class="x_panel">
           <!-- TÍTULO DO PAINEL-->  
           <div class="x_title">
            <h2><small>feriados sem cliente especificado se aplicam a todos os clientes</small></h2>
            <div class="clearfix"></div>
          </div>
          <!-- CONTEÚDO DO PAINEL -->
          <div class="x_content"><br/>
            <!-- FORMULÁRIO DE TROCA-->
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="..\scripts\cadModulos.php" method="post">

              <!-- COMPONENTE CLIENTE -->  
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">CLIENTE:<span class="required">*</span></label>
                <div class="col-md-5 col-sm-6 col-xs-12">
                  <select class="select2_single form-control" tabindex="-1" name="cliente" id="cliente" required>
                    <option value=0>PARA TODOS</option>";
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


              <!-- COMPONENTE DATA -->

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">DATA DO FERIADO<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="data" name='data' class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99/99'" placeholder="12/12" type="text">
                </div>
              </div>

              <!-- COMPONENTE DESCRIÇÃO -->

              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">DESCRIÇÃO<span class="required">*</span>
                </label>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  <input id="descricao" name='descricao' class="form-control col-md-7 col-xs-12" type="text">
                </div>
              </div>


              <!-- LINHA -->
              <div class="ln_solid"></div>
              <!-- BOTÃO SUBMISSÃO -->
              <div class="form-group">
               <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                 <button type="button" id="btnSalvar" class="btn btn-success">Salvar</button>
                 <div class="ln-solid"></div>
               </div>
             </div>
             <!-- FIM COMPONENTES -->
             
             <!-- IDUPDATE -->
             <input type="text" id="idUpdate" name="idUpdate" hidden>

           </form>
           <!-- FIM FORMULARIO -->
         </div>
       </div>
       <div class="clearfix"></div>
       <!-- TABELA DE CONSULTAS DE USUÁRIOS-->
       <div class="x_panel">
        <div class="x_content">
          <!-- consulta usuarios cadastrados no banco-->
          <?php
          include_once "../scripts/Conexao-class.php";
          $conect = new Conexao();
          $link = $conect->getLink();

          $sql = "SELECT * FROM FERIADOS";
          $result = mysqli_query($link, $sql);

          echo "<table id=\"datatable-responsive\" class=\"table table-striped table-bordered dt-responsive nowrap\" cellspacing=\"0\" width=\"100%\">
          <thead>
            <tr>
              <th>CLIENTE</th>
              <th>DESCRIÇÃO</th>
              <th>DATA</th>
              <th>OPÇÕES</th>
            </tr>
          </thead>
          <tbody>";

            while ($row = $result->fetch_assoc()){

              $sql = "SELECT CLI_NREDUZ FROM CLIENTES WHERE CLI_ID=".$row['feriado_cliente'];
              $resultAux = mysqli_query($link, $sql);
              $nomeCliente = $resultAux->fetch_assoc();
              $nomeCliente = $nomeCliente['CLI_NREDUZ'];

              if ($row['feriado_cliente'] == 0) $nomeCliente = 'PARA TODOS';

              echo "<tr>";
              echo "<td>" . $nomeCliente . "</td>";
              echo "<td>" . $row['feriado_descricao']. "</td>";
              echo "<td>" . $row['feriado_data']."</td>";
              echo "<td>
              <form method=post action=\"?id=".$row['feriado_id']."\">
                <button id=".$row['feriado_id']." type=\"submit\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i> Editar </button>
                <button type=\"button\" class=\"btn btn-danger btn-xs\" data-toggle=\"modal\" onclick=\"deletar('".$row['feriado_id']."')\" data-target=\".bs-example-modal-lg\">Deletar</button>
              </form>
            </td>";
            echo '</tr>';
          }

          echo    "</tbody>
        </table>";
        mysqli_free_result($result);
        $conect->fechar();
        ?>
      </div>
    </div>

    <script type="text/javascript">
    function ehDataInvalida (data) {
      var vetorData = data.split("/");
      var dia = vetorData[0];
      var mes = vetorData[1];
      if (dia > 31 || mes > 12)
          return true;
      return false;
    }
      function salvarFeriado() {
        var cliente   = $('#cliente').val();
        var descricao = $('#descricao').val();
        var data      = $('#data').val();
        var id        = $('#idUpdate').val();

        if (ehDataInvalida(data)) {
          alert("Data inválida.");
          return;
        }

        var acao = '';
        if (id == "") id = 0;
        if (id > 0) acao = 'update';
        else acao = 'insert';

        $.ajax({
          type: 'POST',
          url: '../scripts/cadFeriados.php',
          data: {
            acao: acao,
            cliente: cliente,
            descricao: descricao,
            data: data,
            idFeriado: id
          },
          success: function(result) {
            if (result == '1') 
              window.location.replace('feriados.php');
            else alert(result);
          },
          error: function(result) {
            alert(result);
          }
        });
      }

      function deletar(id) {
        $.ajax({
          type: 'POST',
          url: '../scripts/cadFeriados.php',
          data: {
            acao: 'delete',
            idFeriado: id
          },
          success: function(result) {
            if (result == 1) window.location.replace('feriados.php');
            else alert(result);
          },
          error: function(result) {
            alert(result);
          }
        });
      }

      $(document).ready(function() {
        $('#btnSalvar').on('click', function(){
          salvarFeriado();
        });
      });
    </script>
    <!-- FIM TABELA -->

  </div>
</div>

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
<!-- jquery.inputmask -->
<script src="../vendors/jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js"></script>
<!-- bootstrap-daterangepicker -->
<script src="../vendors/moment/min/moment.min.js"></script>
<script src="../vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

<!-- Custom Theme Scripts -->
<script src="../build/js/custom.min.js"></script>

<script>
  $(document).ready(function() {
    $(":input").inputmask();
  });
</script>

<!-- bootstrap-daterangepicker -->
<!-- <script>
  $(document).ready(function() {
    $('#nascimento').daterangepicker({
      singleDatePicker: true,
      calender_style: "picker_3"
    }, function(start, end, label) {
      console.log(start.toISOString(), end.toISOString(), label);
    });
    <?php 
    if (!isset($_GET['id']))
      echo "$('#nascimento').val('')";
    ?>
  });
</script> -->
<!-- /bootstrap-daterangepicker -->

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

<!-- call do botão editar -->

<?php 

if (isset($_GET['id'])) {
  $idFeriado = $_GET['id'];
  include_once "../scripts/Conexao-class.php";
  $conexao = new Conexao();
  $link = $conexao->getLink();

  $sql = "SELECT * FROM feriados 
  WHERE feriado_id=".$idFeriado;
  $result = mysqli_query($link, $sql);
  if (mysqli_num_rows($result) > 0) {
    $dados = $result->fetch_assoc();

    $cliente   = $dados['feriado_cliente'];
    $descricao = $dados['feriado_descricao'];
    $data      = $dados['feriado_data'];

    echo "<script>";
    echo "$('#idUpdate').val('".$idFeriado."');";
    echo "$('#cliente').val(".$cliente.").attr('disabled','true');";
    echo "$('#descricao').val('".$descricao."').change();";
    echo "$('#data').val('".$data."').change();";
    echo "</script>";
  }
}
?>
</body>
</html>

