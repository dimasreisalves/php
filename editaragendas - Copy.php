<?php
if(!isset($_SESSION)) session_start();
if ($_SESSION['userNivel'] != 'ADMINISTRADOR')
{
  echo "<script> window.location.replace('home.php'); </script>";
  exit;
}
?>
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
           <h3>Ferramentas administrativas</h3>
         </div>
       </div> 

       <!-- ROW CADASTROS -->
       <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
         <!-- PAINEL DE CADASTRO -->
         <div class="x_panel">
           <!-- TÍTULO DO PAINEL-->  
           <div class="x_title">
            <h2> Correção de agendas <small> selecione uma agenda para atualizar </small></h2>
            <div class="clearfix"></div>
          </div>
          <!-- CONTEÚDO DO PAINEL -->
          <div class="x_content"><br/>

            <!-- FORMULÁRIO DE CADASTRO-->
            <form class="form-horizontal form-label-left" method="POST" action="salvarAgenda.php">

              <!-- componente cliente-->
              <div class="form-group">

                <!-- cliente -->
                <label class="control-label col-md-3 col-sm-3 col-xs-12">CLIENTE:</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <h2 id="nomeCliente"></h2>
                </div>

                <!-- consultor -->
                <label class="control-label col-md-3 col-sm-3 col-xs-12">CONSULTOR:
                </label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <h2 id="nomeConsultor"></h2>
                </div>

              </div>

              <!-- data da agenda -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">DATA:</label>
                <div class="col-md-3 col-sm-3 col-xs-12">
                  <h2 id="dataOS"></h2>
                </div>
              </div>

              <!-- tipo da agenda -->
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12">TIPO DE AGENDA:</label>
                <div class="col-md-3 col-sm-3 col-xs-12">

                  <select class="form-control" id="tipoAgenda" name="tipoAgenda">
                    <option value="REMOTO">REMOTO</option>
                    <option value="PRESENCIAL">PRESENCIAL</option>
                  </select>
                </div>
              </div>

              <!-- inicio e fim -->  
              <div class="form-group">

                <!-- inicio -->
                <label class="control-label col-md-3 col-sm-3 col-xs-3">INICIO</label>
                <div class="col-md-3 col-sm-3 col-xs-3">
                  <input type="text" name="inicio" id="inicio" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99:99'" value="00:00">
                </div>

                <!-- fim -->
                <label class="control-label col-md-3 col-sm-3 col-xs-3">FIM</label>
                <div class="col-md-3 col-sm-3 col-xs-3">
                  <input type="text" name="fim" id="fim" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99:99'" value="00:00">
                </div>

              </div>

              <!--translado e outros-->
              <div class="form-group">

                <!-- translado -->
                <label class="control-label col-md-3 col-sm-3 col-xs-3">TRANSLADO</label>
                <div class="col-md-3 col-sm-3 col-xs-3">
                  <input type="text" name="translado" id="translado" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99:99'" value="00:00">
                </div>

                <!-- descontos -->
                <label class="control-label col-md-3 col-sm-3 col-xs-3">DESCONTOS</label>
                <div class="col-md-3 col-sm-3 col-xs-3">
                  <input type="text" name="outros" id="outros" class="form-control col-md-7 col-xs-12" data-inputmask="'mask' : '99:99'" value="00:00"> 
                </div>

              </div>

              <!-- COMPONENTE horas totais -->  
              <div class="form-group">
                <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name"> TOTAL DE HORAS:
                </label>
                <div class="col-md-9 col-sm-6 col-xs-12">
                  <input type="text" name="horasTotais" id="horasTotais" value="00:00" hidden>
                  <h2 id="totalHoras"> <strong>00:00</strong> </h2>
                </div>  
              </div>

              <input type="text" name="idAgenda" id="idAgenda" value="0" hidden>
              <!-- LINHA -->
              <div class="ln_solid"></div>
              <div class="clearfix"> </div>
              <!-- BOTÃO SUBMISSÃO -->
              <div class="form-group">
                <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                  <button type="submit" class="btn btn-success" id="btnSalvarOS">Salvar Agenda</button>
                  <button type="reset" class="btn btn-danger" id="cancelar">Cancelar</button>
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

    <!-- FILTRO-->
    <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel">
        <!-- titulo -->
        <div class="x_title">
          <h2>Filtro de agendas</h2>
          <div class="clearfix"></div>
        </div>

         <div class="x_content">
           
           <form id="formFiltro" data-parsley-validate class="form-horizontal form-label-left" >

           <!-- data -->
           <div class="form_group">
             <label class="control_label col-md-21 col-sm-12 col-xs-12">Data</label>
             <div class="col-md-3 col-sm-3 col-xs-3">
             <input type="text" id="data" class="date-picker form-control " data-inputmask="'mask' : '99/99/9999'" placeholder="21/12/2012" type="text">  
             </div>
           </div>
           
           <!-- consultor -->
           <div class="form_group">
             <label class="control_label col-md-12 col-sm-12 col-xs-12">Consultor</label>
             
             <div class="col-md-3 col-sm-3 col-xs-3">
             <select class="form-control col-md-3 col-sm-3 col-xs-3" id="consultor" value=0>
               <option value=0>...</option>
               <?php 
                  include_once "../scripts/Conexao-class.php";
                  $conexao = new Conexao();
                  $link = $conexao->getLink();
                  $sql = "SELECT DISTINCT CONS_ID, CONS_NOME 
                          FROM consultor
                          WHERE CONS_TIPO != ''";
                  $result = mysqli_query($link, $sql);
                  while ($row = $result->fetch_assoc()){
                    $id = $row['CONS_ID'];
                    $nome = $row['CONS_NOME'];
                    echo "<option value='".$id."'>".$nome."</option>";
                  }
                  $conexao->fechar();
                ?>
             </select>
             </div>
           </div>

           <!-- empresa -->
           <div class="form_group">
             <label class="control_label col-md-12 col-sm-12 col-xs-12">Cliente</label>
             <div class="col-md-3 col-sm-3 col-xs-3">
             <select class="form-control col-md-3 col-sm-3 col-xs-3" id="cliente" value=0>
               <option value=0>...</option>
               <?php 
                  include_once "../scripts/Conexao-class.php";
                  $conexao = new Conexao();
                  $link = $conexao->getLink();
                  $sql = "SELECT CLI_ID, CLI_NREDUZ
                          FROM clientes";
                  $result = mysqli_query($link, $sql);
                  while ($row = $result->fetch_assoc()){
                    $id = $row['CLI_ID'];
                    $nome = $row['CLI_NREDUZ'];
                    echo "<option value=".$id.">".$nome."</option>";
                  }
                  $conexao->fechar();
                ?>
             </select>
             </div>
           </div>
           
           <div class="clearfix"></div>
           <br/> 

           <!-- botao -->
           <div class="form-group">
           <div class="col-md-3 col-sm-3 col-xs-3">
              <button class="btn btn-info form-control" id="btnFiltrar" type=button>Filtrar</button>
              <button class="btn btn-danger form-control" id="btnLimpaFiltro" type=button>Limpar Filtro</button>
            </div>
           </div>
           
          </form>
         </div>
        </div>
      </div>
    </div>

    <!-- acao do btnFiltrar -->
    <script type="text/javascript">

      <?php
        $temFiltro = 0;
        $cliente = "";
        $consultor = "";
        $data = "";
        if (isset($_GET['data'])) {
          $temFiltro = 1;
          $dataFiltro = $_GET['data'];
          $cliente = $_GET['cliente'];
          $consultor = $_GET['consultor'];
        }
      ?>

      $(document).ready(function(){
        var filtro = "<?php echo $temFiltro?>";
        
        var data = "<?php echo $dataFiltro?>";
        var consultor = "<?php echo $consultor?>";
        var cliente = "<?php echo $cliente?>";

        if (filtro == 1) {
          $('#data').val(data).change();
          $('#consultor').val(consultor);
          $('#cliente').val(cliente);
        }
      });

      $('#btnFiltrar').on('click', function(){
        var data = $('#data').val();
        var consultor = $('#consultor').val();
        var cliente = $('#cliente').val();

        var url = "editaragendas.php?";
        var filtros = "data="+data+"&";
        filtros    = filtros+"consultor="+consultor+"&";
        filtros    = filtros+"cliente="+cliente+"&";
        window.location.replace(url+filtros);
      });

      $('#btnLimpaFiltro').on('click', function(){
        window.location.replace('editaragendas.php');
      });
    </script>


  <!-- TABELA DE CONSULTA -->
  <div class="row">
   <div class="x_panel">
     <div class="x_content">
      
       <!-- consulta agendas cadastrados no banco-->
       <?php
       include_once "../scripts/Conexao-class.php";
       $conect = new Conexao();
       $db= $conect->getLink();

       $sql = "SELECT * FROM agenda where AGEN_EXPORTADO !='0000-00-00'";

       # checa se existem filtros para mudar a query
       if (isset($_GET['data'])) {
        $data = $_GET['data'];
        # conversao da data
        $vetorData = explode("/", $data);
        $data = $vetorData[2]."-".$vetorData[1]."-".$vetorData[0];

        $consultor = $_GET['consultor'];
        $cliente = $_GET['cliente'];

        $sql .= " AND AGEN_DTAGENDA='".$data."'";
        if ($consultor != 0) $sql .= " AND AGEN_CONSULTOR='".$consultor."'";
        if ($cliente != 0) $sql .= " AND AGEN_CLIENTE_ID='".$cliente."'";
       }
       # echo $sql;
       # fim checagem de filtros

       $result = $db->query($sql);

       echo "<table id=\"datatable-responsive\" class=\"table table-striped table-bordered dt-responsive nowrap\" cellspacing=\"0\" width=\"100%\">
       <thead>
         <tr>
           <th>ID AGENDA</th>
           <th>DATA AGENDA</th>
           <th>CLIENTE</th>
           <th>CONSULTOR</th>
           <th>OPÇÕES</th>
         </tr>
       </thead>
       <tbody>";
        if($result){
         while ($row = $result->fetch_assoc()){

          $id_agenda    = $row['AGEN_ID'];
          $data         = $row['AGEN_DTAGENDA'];
          $id_cliente   = $row['AGEN_CLIENTE_ID'];
          $id_consultor = $row['AGEN_CONSULTOR'];

        // pre processamento das informacoes para o usuario

        // data
          include_once "../scripts/ConversorData-class.php";
          $conversor = new ConversorData();
          $data = $conversor->sql2Brasil($data);

        // cliente
          $sql = "SELECT CLI_NREDUZ FROM clientes WHERE CLI_ID=".$id_cliente;
          $resultCliente = $db->query($sql);
          $nomeCliente = $resultCliente->fetch_assoc();
          $nomeCliente = $nomeCliente['CLI_NREDUZ'];

        // consultor
          $sql = "SELECT CONS_NOME FROM consultor WHERE CONS_ID=".$id_consultor;
          $resultCliente = $db->query($sql);
          $nomeConsultor = $resultCliente->fetch_assoc();
          $nomeConsultor = $nomeConsultor['CONS_NOME'];

          echo "<tr>";
          echo "<td>" . $id_agenda . "</td>";
          echo "<td>" . $data . "</td>";
          echo "<td>" . $nomeCliente . "</td>";
          echo "<td>" . $nomeConsultor . "</td>";
          echo "<td>

          <button id=".$id_agenda." type=\"button\" onclick=\"getData(".$id_agenda.")\" class=\"btn btn-info btn-xs\"><i class=\"fa fa-pencil\"></i>Editar</button>
        </td>";
        echo '</tr>';
      }
      $result->free();
    }
    echo    "</tbody>
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
        <label>Você está prestes a deletar um módulo, tem plena certeza do que está fazendo?</label>
        <br><br>
        <button type="submit" class="btn btn-success">Sim</button>
        <button type="button" class="btn btn-danger" data-dismiss="modal">Não</button>
        <input type="text" name="idDeletar" id="idDeletar" hidden>
        <input type="text" name="idEntidade" id="idEntidade" value="modulos" hidden>
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

<!-- SCRIPT TO UPDATE HOURS -->
<script type="text/javascript">

  /* Atualiza translado do cliente */
  $('#tipoAgenda').on('change', function(){
    var tipoAgenda = $('#tipoAgenda').val();

    if (tipoAgenda == "PRESENCIAL") {
      // atualiza translado do cliente com base no ID da agenda
      var idAgenda = $('#idAgenda').val();
      if (idAgenda != 0) {
        $.ajax({
          type: 'POST',
          url: 'getTranslado.php',
          data: {
            idAgenda: idAgenda
          },
          success: function(result){
            $('#translado').val(result);
            atualizaHoraTotal();
          },
          error: function(result){
            alert(result);
          }
        });
      }
    } else {
      $('#tipoAgenda').val('REMOTO');
      $('#translado').val('00:00');
    }

    atualizaHoraTotal();
  });

  function atualizaHoraTotal() {

    var horaInicio    = $('#inicio').val();
    var horaFim       = $('#fim').val();
    var horaTranslado = $('#translado').val();
    var horaDescontos = $('#outros').val();
    var tipoAgenda = $('#tipoAgenda').val();

    var vHI = horaInicio.split(":");
    var vHF = horaFim.split(":");
    var vHT = horaTranslado.split(":");
    var vHD = horaDescontos.split(":");

    var horasTotais   = parseInt(vHF[0]) - parseInt(vHI[0]) + parseInt(vHT[0]) - parseInt(vHD[0]);
    var minutosTotais = parseInt(vHF[1]) - parseInt(vHI[1]) + parseInt(vHT[1]) - parseInt(vHD[1]);

    if (tipoAgenda == 'REMOTO') {
      horasTotais -= parseInt(vHT[0]);
      minutosTotais -= parseInt(vHT[1]);
    }

    minutosTotais += horasTotais * 60;
    horasTotais = 0;
    while (minutosTotais >= 60) {
      horasTotais++;
      minutosTotais -= 60;
    }

    if (horasTotais < 10) horasTotais = "0"+horasTotais;
    if (minutosTotais < 10) minutosTotais = "0"+minutosTotais;

    $('#totalHoras').html(horasTotais+":"+minutosTotais);
    $('#horasTotais').val(horasTotais+":"+minutosTotais);
  }  

  // quando trocar qualquer tempo
  $('#inicio').on('change', function(){
    atualizaHoraTotal();
  });
  $('#fim').on('change', function(){
    atualizaHoraTotal();
  });
  $('#translado').on('change', function(){
    atualizaHoraTotal();
  });
  $('#outros').on('change', function(){
    atualizaHoraTotal();
  });
  $('#tipoAgenda').on('change', function(){
    atualizaHoraTotal();
  });
</script>

<!-- MY JAVASCRIPT -->
<script type="text/javascript">
  /*
    convenção de ordem de recebimento dos parametros:
    cliente   0
    consultor 1
    data      2
    tipo      3
    inicio    4
    fim       5
    translado 6
    outros    7
    total     8
    */
    function getData (id) {
     $("#idAgenda").val(id);
     $.ajax({
      type: 'POST',
      url: 'recuperaAgenda.php',
      data: {
        id: id
      },
      success: function (result) {
                // alert(result);
                // result
                var vector = result.split("|");
                
                //alert(vector[3]);

                // campos a preencher
                $('#nomeCliente').html(vector[0]);
                $('#nomeConsultor').html(vector[1]);
                $('#dataOS').html(vector[2]);
                $('#tipoAgenda').val(vector[3]);
                $('#inicio').val(vector[4]);
                $('#fim').val(vector[5]);
                $('#translado').val(vector[6]);
                $('#outros').val(vector[7]);
                $('#totalHoras').html(vector[8]);
                $('#horasTotais').val(vector[8]);
              },
              error: function (result) {
                alert(result);
              }
            });
   }

   $("#cancelar").on('click', function() {
    $('#nomeCliente').html('');
    $('#nomeConsultor').html('');
    $('#dataOS').html('');
    $('#tipoAgenda').val('REMOTO');
    $('#totalHoras').html('00:00');
    $('#horasTotais').html('00:00');
  });
</script>

<!-- Custom Theme Scripts --> <script src="../build/js/custom.min.js"></script>

<!-- bootstrap-daterangepicker -->
<script>
  $(document).ready(function() {
    $('#data').daterangepicker({
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

    $('#datatable-responsive').DataTable({
      'order':[0, 'desc']
    });

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
    document.getElementById('descReduz').value = "";
    document.getElementById('descReduz').readOnly = false;
    document.getElementById('descricao').value = "";
    document.getElementById('idUpdate').value = "";
  }
</script>
<!-- /Limpar -->
</body>
</html>

