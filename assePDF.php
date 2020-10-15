<?php
include_once "../scripts/Conexao-class.php";
include_once "../scripts/ConversorData-class.php";

$conversor    = new ConversorData;
$conect       = new Conexao();
$link         = $conect->getLink();
$idOcorrencia = $_GET['id'];

// somatorio de horas para este chamado
$sql = "SELECT * FROM rateio WHERE rateio_chamado=".$idOcorrencia;
$result = mysqli_query($link, $sql);
$hrAux = 0;
$minutosTotais = 0;
while ($row = $result->fetch_assoc()) {
  $hrref = substr($row['rateio_hrref'], 0, 5);
  $horaVetor = explode(":", $hrref);

  $minutosTotais += (int) $horaVetor[0] * 60 + $horaVetor[1];
  while ($minutosTotais >= 60) {
    $minutosTotais -= 60;
    $hrAux++;
  }
}
if ($hrAux < 10) $hrAux = "0".$hrAux;
if ($minutosTotais < 10) $minutosTotais = "0".$minutosTotais;
$stringHorasChamado = "Total de horas trabalhadas na ocorrência: ".$hrAux."h".$minutosTotais."min";
if ($_SESSION['userNivel'] != 'ADMINISTRADOR' ) $stringHorasChamado = " ";

//dados da ocorrencia
$sql             = "SELECT * FROM OCORRENCIAS WHERE OCOR_ID=".$idOcorrencia." LIMIT 1";
$result          = mysqli_query($link, $sql);
$dadosOcorrencia = $result->fetch_assoc();

$descricaoResumida = $dadosOcorrencia['OCOR_DESC_RESUMIDA'];
$descricao         = $dadosOcorrencia['OCOR_DESCRICAO'];
$descricao         = str_replace("&#13;", "<br/>", $descricao);
$descricao         = str_replace("&#13;&#13;", "<br/><br/>", $descricao);
$dataInclusao      = $dadosOcorrencia['OCOR_DTINCLUSAO'];
$prazo             = $dadosOcorrencia['OCOR_DTPRAZO'];
$prazo             = $conversor->sql2Brasil($prazo);
$impacto           = $dadosOcorrencia['OCOR_IMPACTO'];
$contato           = $dadosOcorrencia['OCOR_CONTATO'];

$idModulo          = $dadosOcorrencia['OCOR_ID_MODULOS'];
$idProblema        = $dadosOcorrencia['OCOR_ID_PROB'];
$idCliente         = $dadosOcorrencia['OCOR_ID_CLIENTE'];
$idSolicitante     = $dadosOcorrencia['OCOR_ID_USUARIO'];
$idConsultor       = $dadosOcorrencia['OCOR_CONSULTOR'];

//Descrição do módulo
$sql         = "SELECT MOD_DESCRICAO FROM MODULOS WHERE MOD_ID=".$idModulo;
$resultMod   = mysqli_query($link, $sql);
$dadosModulo = $resultMod->fetch_assoc();
$nomeModulo  = $dadosModulo['MOD_DESCRICAO'];

//Descrição do problema
$sql           ="SELECT PROB_DESCRICAO FROM CADASTRO_PROBLEMAS 
                 WHERE PROB_ID=".$idProblema;
$resultProb    = mysqli_query($link, $sql);
$dadosProblema = $resultProb->fetch_assoc();
$nomeProblema  = $dadosProblema['PROB_DESCRICAO'];

//Descrição do cliente
$sql          = "SELECT CLI_NOME FROM CLIENTES WHERE CLI_ID=".$idCliente;
$resultCli    = mysqli_query($link, $sql);
$dadosCliente = $resultCli->fetch_assoc();
$nomeCliente  = $dadosCliente['CLI_NOME'];

//Descrição do solicitante
$sql              = "SELECT USER_NOME FROM USUARIO WHERE USER_ID=".$idSolicitante;
$resultUser       = mysqli_query($link, $sql);
$dadosUsuario     = $resultUser->fetch_assoc();
$nomeSolicitante  = $dadosUsuario['USER_NOME'];

//Descrição do consultor
$sql            = "SELECT USER_NOME FROM USUARIO WHERE USER_ID=".$idConsultor;
$resultUser     = mysqli_query($link, $sql);
$dadosUsuario   = $resultUser->fetch_assoc();
$nomeConsultor  = $dadosUsuario['USER_NOME'];

$content = "
<page>
<img src='images/pdflogoAsse.png'><br><br><br>

<table style=\"width:100%; table-layout:fixed;\">
  <tr>
    <th>(".$idOcorrencia.") ".$descricaoResumida." <br/> ".$stringHorasChamado." </th>
  </tr>
  <tr>
    <td>
    	<strong>Descrição<br>                  </strong>".$descricao."       <br>
      <strong>Cliente:                    </strong> ".$nomeCliente."     <br>
      <strong>Módulo:                     </strong> ".$nomeModulo."      <br>
      <strong>Problema:                   </strong> ".$nomeProblema."    <br>
      <strong>Solicitante:                </strong> ".$nomeSolicitante." <br>
      <strong>Data de inclusão:           </strong> ".$dataInclusao."    <br>
      <strong>Prazo:                      </strong> ".$prazo."           <br>
      <strong>Contato:                    </strong> ".$contato."         <br>
    </td>
  </tr>
</table>
<br>
<h4>Assentamentos realizados</h4>
";

$sql               =  "SELECT * FROM ASSENTAMENTO 
                       WHERE ASSE_ID_OCORR=".$idOcorrencia." 
                       ORDER BY ASSE_DTINCLUSAO";
$resultAsse        = mysqli_query($link, $sql);

while ($row = $resultAsse->fetch_assoc()) {
  if ($row['ASSE_DESCRICAO'] == "") continue;
  $inclusaoAssentamento  = $row['ASSE_DTINCLUSAO'];
  $descricaoAssentamento = $row['ASSE_DESCRICAO'];
  $idUsuario             = $row['ASSE_USUARIO_CODIGO'];
  $sql                   = "SELECT USER_NOME FROM USUARIO WHERE USER_ID=".$idUsuario;
  $resultUser            = mysqli_query($link, $sql);
  $nomeUsuario           = $resultUser->fetch_assoc();
  $nomeUsuario           = $nomeUsuario['USER_NOME'];

  $content .= "<strong>Apontado por:</strong>".$nomeUsuario." - <strong>
  Em:".$inclusaoAssentamento."</strong>:<br>".$descricaoAssentamento."<br><br>";
}

$content .= 
            "<div style=\"margin-top: 30px; width:100%; heigth: 500px; \"> 
              <h4>Observações</h4>
              <table style=\"border: 1px solid black; width:100%;\">
              <tr>
              <th style=\"width: 100%; padding: 10px\"></th>
              </tr>
              <tr>
              <td style=\"width: 100%; padding: 10px; border-top: 1px solid black\"></td>
              </tr>
              <tr>
              <td style=\"width: 100%; padding: 10px; border-top: 1px solid black\"></td>
              </tr>
              <tr>
              <td style=\"width: 100%; padding: 10px; border-top: 1px solid black\"></td>
              </tr>
              <tr>
              <td style=\"width: 100%; padding: 10px; border-top: 1px solid black\"></td>
              </tr>
              <tr>
              <td style=\"width: 100%; padding: 10px; border-top: 1px solid black\"></td>
              </tr>
              <tr>
              <td style=\"width: 100%; padding: 10px; border-top: 1px solid black\"></td>
              </tr>
              <tr>
              <td style=\"width: 100%; padding: 10px; border-top: 1px solid black\"></td>
              </tr>
              <tr>
              <td style=\"width: 100%; padding: 10px; border-top: 1px solid black\"></td>
              </tr>
              <tr>
              <td style=\"width: 100%; padding: 10px; border-top: 1px solid black\"></td>
              </tr>
              <tr>
              <td style=\"width: 100%; padding: 10px; border-top: 1px solid black\"></td>
              </tr>
              </table>

            </div>";
$content .= "<page_footer>
<em>Este relatório foi gerado em ".date("d/m/Y")." e serve apenas para simples consulta.
</em>
</page_footer>
</page>";

  require_once('html2pdf/html2pdf.class.php');
   $html2pdf = new HTML2PDF('P','A4','en', true, 'UTF-8', array('15', '10', '15', '20'));
   $nomeArquivo = $nomeCliente."-".$idOcorrencia.".pdf";
   $html2pdf->WriteHTML($content);
   $html2pdf->Output($nomeArquivo);
   $conect->fechar();
?>