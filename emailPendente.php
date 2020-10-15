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
           <h3>E-mails para conversão em chamados</h3>
           <a class="btn btn-danger" href="testeEmail.php">Importar novos E-mails</a>
         </div>
       </div>
       <!-- ROW CADASTROS -->
       <div class="row">
        <div class="col-md-12 col-xs-12">


         <!-- TABELA DE CONSULTAS DE USUÁRIOS-->
         <div class="x_panel">
          <div class="x_content">
           <!-- consulta usuarios cadastrados no banco-->
           <?php
           include_once "../scripts/Conexao-class.php";
           $conect = new Conexao();
           $link = $conect->getLink();

           echo "<table id=\"datatable-responsive\" class=\"table table-striped table-bordered dt-responsive\" cellspacing=\"0\" width=\"100%\">
           <thead>
             <tr>
               <th style='width: 100px;'>DATA DE ENVIO</th>
               <th>AÇÃO</th>
               <th>CLIENTE</th>
               <th>ASSUNTO</th>
               <th>DESCRICAO</th>
               <th>ENDEREÇO</th>
               <th>CONTATO</th>
             </tr>
           </thead>
           <tbody>";

            $sql = "SELECT * FROM email_pendente WHERE email_bloqueado != 1";
            $result = mysqli_query($link, $sql);

            while ($row = $result->fetch_assoc()){
              $sql = "SELECT CLI_NREDUZ FROM CLIENTES WHERE CLI_ID=".$row['email_cliente'];
              $resultAux = mysqli_query($link, $sql);
              $nomeCliente = $resultAux->fetch_assoc();
              $nomeCliente = $nomeCliente['CLI_NREDUZ'];

              echo "<tr>";
              echo "<td>" .$row['email_data']. "</td>";
              echo "<td> <a class=\"btn btn-sm btn-info\" href=\"ocorrencias.php?email=".$row['email_id']."\">Converter</a>
                <a onclick='deletarEmail(".$row['email_id'].")' class=\"btn btn-sm btn-danger\" >Bloquear</a>
              </td>";
              echo "<td>" .$nomeCliente. "</td>";
              echo "<td>" .$row['email_assunto']. "</td>";
              echo "<td style='width: 400 px; overflow: none'>" .substr($row['email_descricao'], 0, 144). "...</td>";
              echo "<td>" .$row['email_from']. "</td>";
              echo "<td>" .$row['email_contato']. "</td>";
              echo "</tr>";
            }

            echo 		"</tbody>
          </table>";
          	
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

<script type="text/javascript">
  function deletarEmail(id) {
    var deletar = confirm("Tem certeza que deseja bloquear esta mensagem?");
    if (deletar) {
      window.location.replace("deletaEmail.php?email="+id);
    }
  }
</script>

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
<script>
	function popularAcao(acao)
	{
		document.getElementById('acao').value = acao;
	}
</script>

</body>
</html>

