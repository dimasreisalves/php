<?php
class Carteiro
{
	private $destinatario;
	private $assunto;
	private $mensagem;

	function __construct ($destinatario, $assunto, $mensagem)
	{
		$this->destinatario = $destinatario;
		$this->assunto      = $assunto;
		$this->mensagem     = $mensagem;
	}

	function entregar($tipo, $idOcorrencia) 
	{ 
		include_once "../paginas/mailer/class.phpmailer.php";
		define('GUSER', 'scweb@solucaocompacta.com.br');
		define('GPWD', 'CBN@2017');
		global $error;
		$mail = new PHPMailer();
		$mail->CharSet = "UTF-8";
		$mail->IsSMTP();		
		$mail->SMTPDebug = 1;		
		$mail->SMTPAuth = true;		
		$mail->SMTPSecure = 'ssl';	
		$mail->Host = 'email-ssl.com.br';
		$mail->Port = 465;  		
		$mail->Username = GUSER;
		$mail->Password = GPWD;
		$mail->SetFrom('scweb@solucaocompacta.com.br', 'Solução Compacta');
		$mail->AddAddress($this->destinatario);
		if ($tipo == 'cc_admin'){
			include_once "../scripts/Conexao-class.php";
			$conexao = new Conexao();
			$link    = $conexao->getLink();
			$sql     = "SELECT USER_LOGIN FROM USUARIO WHERE USER_NIVEL='ADMINISTRADOR'";
			$result  = mysqli_query($link, $sql);
			while ($row = $result->fetch_assoc())
				$mail->addCC($row['USER_LOGIN']);
			$conexao->fechar();
		}
		else if ($tipo == 'cad_assentamento'){
			include_once "../scripts/Conexao-class.php";
			$conexao = new Conexao();
			$link    = $conexao->getLink();

			$sql     = "SELECT DISTINCT USER_LOGIN,USER_ID, OCOR_CONSULTOR FROM USUARIO
			INNER JOIN ASSENTAMENTO ON ASSE_USUARIO_CODIGO = USER_ID
			INNER JOIN OCORRENCIAS ON OCOR_ID = ASSE_ID_OCORR
			WHERE OCOR_ID=".$idOcorrencia;

			$novoConsutor = true;
			$consultor = 0;
			$result = mysqli_query($link, $sql);
			while ($row = $result->fetch_assoc())
			{
				if ($row['USER_ID'] == $row['OCOR_CONSULTOR']) $novoConsutor = false;
				$mail->addCC($row['USER_LOGIN']);
				$consultor=$row['OCOR_CONSULTOR'];
			}

			if ($novoConsutor)
			{
				$sql = "SELECT USER_LOGIN FROM USUARIO WHERE USER_ID=".$consultor." LIMIT 1";
				$result = mysqli_query($link, $sql);
				$emailCons = $result->fetch_assoc();
				$emailCons = $emailCons['USER_LOGIN'];
				$mail->addCC($emailCons);
			}
			$conexao->fechar();
		}
		$mail->Subject = $this->assunto;
		
		$mail->IsHTML(true);
		$mail->AddEmbeddedImage('images/email-logo.png', 'email-logo');
		$mail->Body = $this->mensagem;
		
		if(!$mail->Send()) {
			//echo 'Mail error: '.$mail->ErrorInfo; 
			return false;
		} else {
			//echo 'Mensagem enviada!';
			return true;
		}
	}
}
?>