<?php
include_once "../scripts/Conexao-class.php";
include_once "../scripts/ConversorData-class.php";
$conversor = new ConversorData;
$conect = new Conexao();
$link = $conect->getLink();
$idAgenda = $_GET['id'];
if (isset($_GET['idConsultor'])){

  $idConsultor = $_GET['idConsultor'];
  $dataSql = $_GET['dataSql'];
  $dataFimSql = $_GET['dataFimSql'];
  $idCliente = $_GET['idCliente'];

  /*Busca todas as ocorrencias do cliente entre as datas de inicio e fim definidas*/
  $sqlHoras = "SELECT DISTINCT OCOR_ID, OCOR_DESC_RESUMIDA, OCOR_ID_CLIENTE, OCOR_ID_MODULOS FROM ASSENTAMENTO
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
  order by ocor_id_modulos";

}

//dados da agenda
$sql = "SELECT * FROM AGENDA WHERE AGEN_ID=".$idAgenda." LIMIT 1";
$result = mysqli_query($link, $sql);
$dadosAgenda = $result->fetch_assoc();

//dados do consultor
$idConsultor = $dadosAgenda['AGEN_CONSULTOR'];
$sql = "SELECT CONS_NOME FROM CONSULTOR WHERE CONS_ID=".$idConsultor." LIMIT 1";
$resultCons = mysqli_query($link, $sql);
$dadosConsultor = $resultCons->fetch_assoc();
//dados do cliente
$idCliente = $dadosAgenda['AGEN_CLIENTE_ID'];
$sql = "SELECT * FROM CLIENTES WHERE CLI_ID=".$idCliente." LIMIT 1";
$resultCli = mysqli_query($link, $sql);
$dadosCliente = $resultCli->fetch_assoc();


//consultor
$nomeConsultor = $dadosConsultor['CONS_NOME'];

//cliente
$nomeCliente = $dadosCliente['CLI_NOME'];
$nomeClienteAbrev = $dadosCliente['CLI_NREDUZ'];
$cnpjCliente = $dadosCliente['CLI_CNPJ'];
$enderecoCliente = $dadosCliente['CLI_END'];
$cidadeCliente = $dadosCliente['CLI_MUN'];
$estadoCliente = $dadosCliente['CLI_EST'];
$cepCliente = $dadosCliente['CLI_CEP'];
$localizacaoCliente = $enderecoCliente." - ".$cidadeCliente." - ".$estadoCliente." - ".$cepCliente;
$telefoneCliente = $dadosCliente['CLI_TEL'];

//agenda
$tarefas = $dadosAgenda['AGEN_TEXTO'];
if ($tarefas == '-1')
{
  $tarefas = "<font style='color: red'>A ordem de serviço ainda não foi salva para exibir seus assentamentos, por favor, salve a ordem de serviço e gere novamente o relatório!</font>";
}
else if($tarefas == "")
{
  $tarefas = "<font style='color: red'>Nenhum assentamento foi encontrado ao salvar esta OS!</font>";
}
$inicio = $dadosAgenda['AGEN_HRINICIO'];
$tipo = $dadosAgenda['AGEN_TIPO'];
$fim = $dadosAgenda['AGEN_HRFIM'];
$translado = $dadosAgenda['AGEN_TRANSLADO'];
$descontos = $dadosAgenda['AGEN_DESCONTOS'];
$total = $dadosAgenda['AGEN_HRTOTAL'];
$data = $dadosAgenda['AGEN_DTAGENDA'];
$data = $conversor->sql2Brasil($data);

$faturado = $dadosAgenda['AGEN_FATURADO'];
if ($faturado == '1') $faturado = 'ORDEM DE SERVIÇO FATURADA';
else $faturado = 'ORDEM DE SERVIÇO NÃO FATURADA';

$content = "
<page>
  <img src='images/pdflogo.png'><br><br><br>

  <table style=\"width:100%; table-layout:fixed;\">
    <tr>
      <th>Prezado</th>

    </tr>
    <tr>
      <td>
       Cliente: ".$nomeCliente."<br>
       CNPJ/CPF: ".$cnpjCliente."<br>
       Endereço: ".$localizacaoCliente."<br>
       Cidade: ".$cidadeCliente."<br>
       Telefone: ".$telefoneCliente."<br><br>


       <strong>Solução Compacta Consultoria em Sistema de Informações Ltda ME</strong> <br>
       CNPJ: 04.538.822/0001-81	<br>
       Estr. do Embirussu, 18 - Sobre Loja - Sala 01 <br>
       Bairro: Jardim Santa Brigida – Carapicuiba – SP – CEP: 06900-000 <br>
       Telefone: (11) 4447 3671 <br>
       www.solucaocompacta.com.br
     </td>
   </tr>
   <tr>
   <td>
    <label id='consultor'> <strong>Consultor Solução Compacta: ".$nomeConsultor." </strong></label><br>
    </td>
   </tr>
 </table><br>
 <div style=\"width=100%;\">
  <table >
    <tr>
     <th>Data &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
     <th>Inicio &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
     <th>Término &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
     <th>Translado &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
     <th>Descontos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
     <th>Total &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
   </tr>
   <tr>
     <td>".$data."</td>
     <td>".$inicio."</td>
     <td>".$fim."</td>
     <td>".$translado."</td>
     <td>".$descontos."</td>
     <td>".$total."</td>
   </tr>
 </table>
</div>

<div id=\"Rateio tarefas\">
  <p><strong>RATEIO TAREFAS</strong></p>";

if (isset($_GET['idConsultor'])) {
// armazenar os valores
$resultHoras = mysqli_query($link, $sqlHoras);

// para cada ocorrencia
  $modulo = 0;
  $nomeModulo = "";
  $horasModulo = "00:00";
  $somatorioMinutos = 0;
  $stringHoraFinal = "00:00";

  while ($rowHoras = $resultHoras->fetch_assoc()) {
    $moduloOcorrencia = $rowHoras['OCOR_ID_MODULOS'];

    if ($moduloOcorrencia != $modulo) {
      if ($modulo != 0) {
        //printa o que foi calculado ate o momento
        $horaAux = 0;
        $somatorioMinutos = round($somatorioMinutos);
        while ($somatorioMinutos >= 60) {
          $horaAux++;
          $somatorioMinutos -= 60;
        }

        $horaTexto = (int) $horaAux;
        $minutoTexto = round ($somatorioMinutos);

        if ($horaTexto < 10) $horaTexto = "0".$horaTexto;
        if ($minutoTexto < 10) $minutoTexto = "0".$minutoTexto;

        $stringHoraFinal = $horaTexto."h".$minutoTexto."min";
        $content .= "<label><strong>".$nomeModulo."</strong>:".$stringHoraFinal."</label><br>";
      }

      $modulo = $moduloOcorrencia;
      $somatorioHorasModulo = 0;
    // nome do modulo
      $sqlAux = "SELECT MOD_DESCRICAO
      FROM MODULOS
      WHERE MOD_ID=".$moduloOcorrencia;
      $resultAux = mysqli_query($link, $sqlAux);
      $nomeModulo = $resultAux->fetch_assoc();
      $nomeModulo = $nomeModulo['MOD_DESCRICAO'];
    }

    // busca valor da hora no rateio para a agenda
    $sqlAux = "SELECT * FROM rateio WHERE rateio_agenda=".$idAgenda." AND rateio_chamado=".$rowHoras['OCOR_ID'];
    $resultAux = mysqli_query($link, $sqlAux);
    if (mysqli_num_rows($resultAux) > 0) {
      $dadosRateio = $resultAux->fetch_assoc();

      $hrref = $dadosRateio['rateio_hrref'];
      $vetor = explode(":", $hrref);
      $hrs = $vetor[0];
      $min = $vetor[1];
      $seg = 0;
      if (strpos($vetor[1], ".")) {
        $seg = explode(".", $vetor[1]);
        $min = $seg[0];
        $seg = $seg[1];
      }

      $seg = $seg/10 * 60;
      if ($seg < 10) $seg = '0'.$seg;

      $hrTexto = $hrs.":".$min.":".$seg."s";

      // imprime a ocorrencia (id) ocor_desc_resumida: hrref
      $content .= "(".$rowHoras['OCOR_ID'].") ".$rowHoras['OCOR_DESC_RESUMIDA'].": ".$hrTexto." <br/>";

      $vetorHoraRateio = explode(":", $dadosRateio['rateio_hrref']);

      $somatorioMinutos += $vetorHoraRateio[0] * 60 + $vetorHoraRateio[1];

    }

  }

     //printa o que foi calculado ate o momento para o ultimo modulo
  $horaAux = 0;
  $somatorioMinutos = round($somatorioMinutos);
  while ($somatorioMinutos >= 60) {
    $horaAux++;
    $somatorioMinutos -= 60;
  }

  $horaTexto = $horaAux;
  $minutoTexto = $somatorioMinutos;

  if ($horaTexto < 10) $horaTexto = "0".$horaTexto;
  if ($minutoTexto < 10) $minutoTexto = "0".$minutoTexto;

  $stringHoraFinal = $horaTexto."h".$minutoTexto."min";
  $content .= "<label><strong>".$nomeModulo."</strong>: ".$stringHoraFinal."</label><br/>";
}

  $content .= "</div>

  <br>
  <h4>Tarefas realizadas</h4>
  <label style=\"margin-top: -10px;\">TIPO DE ATENDIMENTO: ".$tipo."</label><br>
  <label style=\"margin-top: -10px;\"><strong>".$faturado."</strong></label>
  <br><br>
  ".$tarefas."";

  $content .= "

  <page_footer>
  <em>Caso haja alguma restrição ao trabalho ou ao pagamento parcial ou total desta ordem de serviço, deverá ser comunicado a solução compacta via carta, fax ou e-mail no prazo de 48 horas</em>
  </page_footer>
</page>";

require_once('html2pdf/html2pdf.class.php');
ob_start();
$html2pdf = new HTML2PDF('P','A4','en', true, 'UTF-8', array('15', '10', '15', '20'));
$nomeArquivo = $nomeConsultor."-".$data."-".$nomeClienteAbrev.".pdf";
$html2pdf->WriteHTML($content);
$html2pdf->Output($nomeArquivo);
$conect->fechar();
?>
