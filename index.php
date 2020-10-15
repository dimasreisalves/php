<?php
if (!isset($_SESSION)) session_start();
if (isset($_SESSION['userNome'])) echo "<script> window.location.replace('home.php'); </script>";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/php; charset=UTF-8">
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Solução Compacta </title>

  <!-- Bootstrap -->
  <link href="../vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="../vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
  <!-- NProgress -->
  <link href="../vendors/nprogress/nprogress.css" rel="stylesheet">
  <!-- Animate.css -->
  <link href="../vendors/animate.css/animate.min.css" rel="stylesheet">
  <!-- bootstrap-wysiwyg -->
  <link href="../vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">

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

  <style type="text/css">
    .novo {
      display: inline-block;
      color: white;
    }
    #email:focus {

    }
  </style>

</head>

<body class="nav-md" onload="loadOff()">
  <!-- LOADER -->
  <div id="loader-wrapper" hidden>
    <div id="loader"></div>
  </div>
  <!-- /LOADER -->
  <div>
    <a class="hiddenanchor" id="signup"></a>
    <a class="hiddenanchor" id="signin"></a>

    <script type="text/javascript">
      $(document).ready(function(){
        $('#login').css('color','#fff');
        $('#senhalbl').css('color','#fff');
        $('#solucao').css('color','#fff');
        $('#recuperacao').css('color','#fff');
        $('#voltarLogin').css('color','#fff');
      });
    </script>

    <div class="login_wrapper">
      <div class="animate form login_form">
        <section class="login_content">
          <img src="images/logo.png"/>	
          <form  method="post" action="../scripts/login.php">
            <h1 id='login'>Login</h1>
             <p class="novo">Você pode fazer login com seu ID</p>
            <div>
              <input name="email" type="text" id="emailLogin" class="form-control" placeholder="Seu e-mail ou (ID)" required="" />
            </div>
           
            <div>
              <input name="senha" type="password" class="form-control" placeholder="Sua senha"/>
            </div>
            <div>
              <input style="margin:auto;" type=submit class="btn btn-info" value="Entrar">
               
              <a style="padding: 10px; margin-top: -2px;"class="btn btn-success" href="registroUsuario.php">Quero me cadastrar!
              </a>

              <p style="margin-left: -65%; margin-top: 10px;" class="change_link">
                <a href="#signup" id='senhalbl' class="to_register"> Esqueceu a senha?</a>
              </p>
              
              <div>
              </div>
              <div class="clearfix"></div>

              <div class="separator">


                <h3 id='solucao'><i class="fa fa-at" ></i> Solução Compacta</h3>
              </div>
            </div>

          </form>
        </section>
      </div>

      <div id="register" class="animate form registration_form">
        <section class="login_content">
          <form>
            <h1 id='recuperacao'>Recuperar Senha</h1>
            <div>
              <input type="email" id='email' class="form-control" placeholder="seu@email.com" required="" />
            </div>
            <div>
              <a class="btn btn-default submit" id='recuperarSenha'>Recuperar senha</a>
            </div>
            <div class="clearfix"></div>
            <div class="separator">
              <p class="change_link">
                <a href="#signin" class="to_register" id='voltarLogin'>Já sou cadastrado!</a>
              </p>
            </div>
          </form>
        </section>
        <label id="processo"></label>
      </div>
    </div>
  </div>

  <!-- recuperar senha -->
  <script type="text/javascript">
    $(document).ready(function(){
      $(document).ajaxSend(function(){
        $('#processo').html('Processando seu pedido...');
        $('#processo').css('color', 'orange');
      });
      $('#recuperarSenha').on('click', function(){
        var email = $('#email').val();
        
        var usuario = email.substr(0, email.indexOf('@'));
        var dominio = email.substr(email.indexOf('@')+1, email.length-1);

        if ((usuario.length >=1) &&
          (dominio.length >=3) && 
          (usuario.search("@")==-1) && 
          (dominio.search("@")==-1) &&
          (usuario.search(" ")==-1) && 
          (dominio.search(" ")==-1) &&
          (dominio.search(".")!=-1) &&      
          (dominio.indexOf(".") >=1)&& 
          (dominio.lastIndexOf(".") < dominio.length - 1))
        {
          $.ajax({
            type: 'POST',
            url: 'recuperaSenha.php',
            data:
            {
              email: email
            },
            success: function(result) {
              alert(result);
              $('#processo').html('Um email de recuperação foi enviado para o endereço fornecido!');
              $('#processo').css('color', '#aaffaa');
            },
            error: function(result) {
              alert(result);
            }
          })
        }
        else
        {
          alert('Email invalido!');
        }
      });
    });
  </script>
  <!-- /recuperar senha -->  

  <!-- novo -->
  <script type="text/javascript">
    $(document).ready(function() {
      $('#emailLogin').focus();
    })
  </script>

</body>
</html>
