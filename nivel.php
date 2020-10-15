
<!-- CADASTRO DE NÍVEL -->
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
            <h2> Cadastro de níveis <small> insira os dados abaixo</small></h2>
            <div class="clearfix"></div>
          </div>
          <!-- CONTEÚDO DO PAINEL -->
          <div class="x_content"><br/>
            <!-- FORMULÁRIO DE CADASTRO-->
            <form id="demo-form2" data-parsley-validate class="form-horizontal form-label-left" action="..\scripts\cadNivel.php" method="post">
              <!-- COMPONENTE DESCRIÇÃO-->					
              <div class="form-group">
               <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">DESCRIÇÃO<span class="required">*</span>
               </label>
               <div class="col-md-6 col-sm-6 col-xs-12">
                 <input type="name" name="descricao" id="descricao" class="form-control col-md-7 col-xs-12" placeholder="Descrição do nível" required>
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
             <input type="text" id="idUpdate" name="idUpdate" hidden>
           </form>
           <!-- FIM FORMULARIO -->
         </div>
       </div>
     </div>
   </div>


   <!-- TABELA DE CONSULTAS DE USUÁRIOS-->
   <div class="x_panel">
    <div class="x_content">
     <!-- consulta usuarios cadastrados no banco-->
     <?php
     include_once "../scripts/Conexao-class.php";
     $conect = new Conexao();
     $db= $conect->getLink();
     $result = $db->query('SELECT * FROM cadastro_nivel');

     echo "<table id=\"datatable-responsive\" class=\"table table-striped table-bordered dt-responsive nowrap\" cellspacing=\"0\" width=\"100%\">
     <thead>
       <tr>
         <th>ID NÍVEL</th>
         <th>DESCRIÇÃO</th>
         <th>OPÇÕES</th>
       </tr>
     </thead>
     <tbody>";
      if($result){
       while ($row = $result->fetch_assoc()){
        echo "<tr>";
        echo "<td>" . $row['NIV_ID'] . "</td>";
        echo "<td>" . $row['NIV_DESCRICAO'] . "</td>";
        echo "<td>
        <form method=post action =\"?id=".$row['NIV_ID']."\">
         <button id=".$row['NIV_ID']." type=\"submit\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i> Editar </button>
         <button type=\"button\" onclick=\"popularDeletar(".$row['NIV_ID'].")\" class=\"btn btn-danger btn-xs\" data-toggle=\"modal\" data-target=\".bs-example-modal-lg\">Deletar</button>
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
        <input type="text" name="idEntidade" id="idEntidade" value="nivel" hidden>
      </form>
    </div>
    <!-- FIM CONTEÚDO DO MODAL -->
  </div>
</div>
</div>
<script>
	function popularDeletar(id)
	{
		document.getElementById('idDeletar').value = id;
	}
</script>
<!-- FIM DO MODAL -->

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
<!-- Limpar -->
<script>
	function limpar()
	{
		document.getElementById('descricao').value = "";
		document.getElementById('idUpdate').value = "";
	}
</script>
<!-- /Limpar -->
</body>
</html>
<!-- puxa campos para update -->
<?php
if(@$_GET['id'] > 0)
{
	$id = $_GET['id'];
	include_once "../scripts/Conexao-class.php";
	$conect = new Conexao();
	$db = $conect->getLink();
	$sql = "SELECT * FROM cadastro_nivel WHERE NIV_ID=".$id;
	$result = $db->query($sql);
	$dados = $result->fetch_assoc();
	
	echo "<script> document.getElementById('descricao').value        = '".$dados['NIV_DESCRICAO']."' </script>";
	echo "<script> document.getElementById('idUpdate').value =".$id.";</script>";
	
	mysqli_free_result($result);
	$conect->fechar();
}
exit;
?>
