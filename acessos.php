<?php
if (!isset($_SESSION)) session_start();
class MontaMenu
{

	function montaMenu ()
	{
		if(!isset($_SESSION['userNome']))
		{
			echo "<script> window.location.replace('index.php'); </script>";
			exit;
		}
		$data = date('d/m/Y');
		$mensagem = "".$data." - Logout";
		echo "
		<!-- MENU DE NAVEGAÇÃO LATERAL -->
    <div class=\"col-md-3 left_col\">
      <div class=\"left_col scroll-view\">

        <div class=\"navbar nav_title\" style=\"border: 0;\">
          <a href=\"home.php\" class=\"site_title\"><i class=\"fa fa-home\"></i> <span>Home</span></a>
        </div>

        <div class=\"clearfix\"></div>

        <!-- menu profile quick info -->
        <div class=\"profile\">
          <div class=\"profile_pic\">
            <img src=\"images/avatar.jpg\" alt=\"...\" class=\"img-circle profile_img\">
          </div>
          <div class=\"profile_info\">
            <span>Bem vindo(a)</span>
            <h2>".$_SESSION['userNome']." <br> ID: (".$_SESSION['userId'].")</h2>
          </div>

        </div>
        <!-- /menu profile quick info -->

        <div class=\"clearfix\"></div>

        <!-- MENU LATERAL -->
        <div id=\"sidebar-menu\" class=\"main_menu_side hidden-print main_menu\">
          <div class=\"menu_section\">
           <br>
           <h3> Cadastros e outras tarefas </h3>
           <!-- PAGINAS DE CADASTRO (TODAS)-->
           <ul class=\"nav side-menu\">
             ";

		#itens do menu
             //menu senha
             echo "
            <!-- Meus dados -->
            <li><a><i class=\"fa fa-user\"></i> Meus dados <span class=\"fa fa-chevron-down\"></span></a>
              <ul class=\"nav child_menu\">
                <li><a href=\"trocaSenha.php\">Trocar Senha</a></li>
              </ul>
            </li>";


             if($_SESSION['userNivel'] == 'ADMINISTRADOR') {
              echo "<li><a><i class=\"fa fa-database\"></i> Para administradores<label style='color: red';>(NOVO!)</label><span class=\"fa fa-chevron-down\"></span></a>
              <ul class=\"nav child_menu\">
                <li><a href=\"editaragendas.php\">Correção de agendas</a></li>
              </ul>
            </li>";

              echo "
                 <!-- MENU PARÂMETROS-->
               <li><a><i class=\"fa fa-code\"></i> Parâmetros <span class=\"fa fa-chevron-down\"></a>
                <ul class=\"nav child_menu\">
                 <li><a href=\"modulos.php\">Módulos</a></li>
                 <li><a href=\"modulos.php\">Módulos</a></li>
                 <li><a href=\"problemas.php\">Problemas</a></li>
                 <li><a href=\"feriados.php\">Feriados</a></li>
                 <li><a href=\"tipodedespesas.php\">Tipo de despesas</a></li>
               </ul>
             </li>";
             }

           if ($_SESSION['userNivel'] != 'USUÁRIO')
           {
            echo "
            <!-- MENU CADASTROS-->
            <li><a><i class=\"fa fa-clone\"></i> Cadastros <span class=\"fa fa-chevron-down\"></span></a>
              <ul class=\"nav child_menu\">
                <li><a href=\"usuario.php\">Usuários</a></li>";

                if ($_SESSION['userNivel'] == 'ADMINISTRADOR')
                  echo "
                <li><a href=\"clientes.php\">Clientes</a></li>
                <li><a href=\"dominios.php\">Domínios</a></li>
                <li><a href=\"dominios.php\">Domínios</a></li>
                ";
                echo "</ul>
              </li>";
							 if ($_SESSION['userNivel'] == 'ADMINISTRADOR' || ($_SESSION['userNivel'] == "CONSULTOR" && $_SESSION['userTipo'] == "INTERNO")) {
								echo "
		            <!-- MENU PROJETOS-->
		            <li><a><i class=\"fa fa-clone\"></i> Projetos <span class=\"fa fa-chevron-down\"></span></a>
		              <ul class=\"nav child_menu\">";

										if ($_SESSION['userNivel'] == 'ADMINISTRADOR')
											echo "<li><a href=\"projetos.php\">Cadastro de projetos</a></li>";

										echo "<li><a href=\"rel_projetos.php\">Relação projetos x ocorrências</a></li>";
		                echo "</ul>
		              </li>";
								}
            }
            if ($_SESSION['userNivel'] == 'ADMINISTRADOR') {
              echo "<li><a><i class=\"fa fa-edit\"></i> Logs <span class=\"fa fa-chevron-down\"></span></a>
              <ul class=\"nav child_menu\">
                <li><a href=\"logacessos.php\">Log de acesso</a></li>
                <li><a href=\"atendimentos.php\">Log de atendimento</a></li>
                <li><a href=\"ticketsadistribuir.php\">Tickets a distribuir</a></li>
                <li><a href=\"trocaeventos.php\">Troca de eventos</a></li>
                <li><a href=\"exportaRateio.php\">Exportar rateio</a></li>
              </ul>
            </li>";
          }

            echo "<!-- FIM MENU  -->  ";

            echo "<!-- MENU MOVIMENTAÇÃO -->
            <li><a><i class=\"fa fa-globe	\"></i> Movimentação <span class=\"fa fa-chevron-down\"></span></a>
              <ul class=\"nav child_menu\">
                ";
                if($_SESSION['userNivel'] != 'USUÁRIO')
                  echo "<li><a href=\"agenda.php\">Agenda</a></li> ";
                echo "<li><a href=\"agenda 2.php\">Agenda 2</a></li> ";
                echo "<li><a href=\"ocorrencias.php\">Abertura de Ticket</a></li>";
                echo "<li><a href=\"filtrarocorrencias.php\">Filtrar ocorrências</a></li>";
                if($_SESSION['userNivel'] != 'USUÁRIO' && $_SESSION['userNivel'] != 'CONSULTOR')
                  echo "<li><a href=\"emailPendente.php\">EMAIL</a></li>
                <li><a href=\"page_404.html\">Relatório de despesas</a></li>";
                echo "</ul>
              </li>
            </ul>
          </div>";

          if ($_SESSION['userNivel'] == 'ADMINISTRADOR')
            echo "
          <div class=\"menu_section\">
            <h3>Análises e indicadores <br></h3>
            <ul class=\"nav side-menu\">
              <li><a href='visaogeral.php'><i class=\"fa fa-line-chart\"></i> Visão geral <span class=\"fa fa-chevron-down\"></span></a>
                <!-- <ul class=\"nav child_menu\">

              </ul> -->
            </li>
          </ul>
        </div>

        <div class=\"menu_section\">
          <h3> Painel de visualização geral </h3>
          <ul class=\"nav side-menu\">
            <li><a><i class=\"fa fa-eye\"></i> Painel compacta <span class=\"fa fa-chevron-down\"></span></a>

            </li>
          </ul>
        </div>";
        echo "
      </div>
      <!-- FIM MENU LATERAL -->
    </div>
  </div>
  <!-- FIM MENU DE NAVEGAÇÃO LATERAL -->

  <!-- MENU TOPO -->
  <div class=\"top_nav\">
    <div class=\"nav_menu\">
      <nav>
        <div class=\"nav toggle\">
          <a id=\"menu_toggle\"><i class=\"fa fa-bars\"></i></a>


        </div>

        <ul class=\"nav navbar-nav navbar-right\">

          <li class=\"\">
            <a class=\"user-profile dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
              <label> Trabalhar com cliente específico:</label>

              <select tabindex=\"-1\" class=\"form-control \" name=\"clienteX\" id=\"clienteX\" value=0>
                <option value=0>NENHUM</option>";
                include_once "../scripts/Conexao-class.php";
                $conect = new Conexao();
                $link = $conect->getLink();

                //se administrador
                if ($_SESSION['userNivel'] == 'ADMINISTRADOR')
                  $sql = "SELECT * FROM clientes ORDER BY CLI_NREDUZ";

                //se consultor interno
                if ($_SESSION['userNivel'] == 'CONSULTOR' && $_SESSION['userTipo'] == 'INTERNO') {
                  $endereco = $_SERVER['REQUEST_URI'];
                  $sql = "SELECT * FROM clientes ORDER BY CLI_NREDUZ";
                }

                //se usuario ou consultor externo
                if ($_SESSION['userNivel'] == 'USUÁRIO' ||
                  ($_SESSION['userNivel'] == 'CONSULTOR'
                    && $_SESSION['userTipo'] == 'EXTERNO'))
                  $sql = "SELECT * FROM clientes WHERE CLI_ID=".$_SESSION['userEmpresa']." ORDER BY CLI_NREDUZ";


                $result = mysqli_query($link,$sql);
                while ($row = $result->fetch_assoc())
                {
                  echo "<option value=\"".$row['CLI_ID']."\"> ".$row['CLI_NREDUZ']." </option>";
                  ;
                }

                echo"
                </select>
                <!-- Cliente X -->
                <script type=\"text/javascript\">
                  $(document).ready(function(){
                    $('#clienteX').on('change',function(){
                      var consultor = $('#consultorX').val();
                      var cliente = $('#clienteX').val();

                      $.ajax({
                        type: 'POST',
                        url: 'setSession.php',
                        data:
                        {
                          cliente: cliente,
                          consultor: consultor
                        },
                        success: function(result)
                        {
                          location.reload();
                        },
                        error: function(result){
                          alert('Erro ao configurar cliente');
                        }
                      });

                    });
                  })
                </script>
                <!-- /ClienteX -->
              </a>
            </li>

            <li class=\"\">
            <a class=\"user-profile dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
              <label> Trabalhar com consultor específico:</label>

              <select tabindex=\"-1\" class=\"form-control \" name=\"consultorX\" id=\"consultorX\" value=0>
                <option value=0>NENHUM</option>";

                //se administrador
                if ($_SESSION['userNivel'] == 'ADMINISTRADOR')
                  $sql = "SELECT * FROM CONSULTOR  INNER JOIN usuario  ON USER_ID = CONS_ID AND USER_STATUS = 1 WHERE CONS_BLOQUEADO != 1  AND CONS_TIPO != '' ORDER BY CONS_NOME";

                //se consultor interno
                if ($_SESSION['userNivel'] == 'CONSULTOR' && $_SESSION['userTipo'] == 'INTERNO') {
                  $endereco = $_SERVER['REQUEST_URI'];
                  $posHome = strpos($endereco, 'home');
                  $posAgenda = strpos($endereco, 'agenda');

                  if ($posHome)
                    $sql = "SELECT * FROM CONSULTOR  INNER JOIN usuario  ON USER_ID = CONS_ID AND USER_STATUS = 1 WHERE CONS_BLOQUEADO != 1  AND CONS_TIPO != '' ORDER BY CONS_NOME";

                  if ($posAgenda || strpos($endereco,'filtrarocorrencias'))
                    $sql = "SELECT * FROM CONSULTOR WHERE CONS_ID=".$_SESSION['userId']." ORDER BY CONS_NOME";
                }



                $result = mysqli_query($link,$sql);
                while ($row = $result->fetch_assoc())
                {
                  if ($row['CONS_NOME'] == 'ADMINISTRADOR') continue;
                  echo "<option value=\"".$row['CONS_ID']."\"> ".$row['CONS_NOME']." </option>";
                }

                echo"
                </select>
                 <!-- Consultor X -->
                <script type=\"text/javascript\">
                  $(document).ready(function(){
                    $('#consultorX').on('change',function(){
                      var consultor = $('#consultorX').val();
                      var cliente = $('#clienteX').val();
                      $.ajax({
                        type: 'POST',
                        url: 'setSession.php',
                        data:
                        {
                          consultor: consultor,
                          cliente: cliente
                        },
                        success: function(result)
                        {
                          location.reload();
                        },
                        error: function(result){
                          alert('Erro ao configurar consultor');
                        }
                      });

                    });
                  })
                </script>
                <!-- /Consultor -->
              </a>
            </li>

            <li class=\"\">
              <a href=\"javascript:;\" class=\"user-profile dropdown-toggle\" data-toggle=\"dropdown\" aria-expanded=\"false\">
                ".$mensagem."
                <span class=\" fa fa-angle-down\"></span>
              </a>
              <ul class=\"dropdown-menu dropdown-usermenu pull-right\">
                <li><a id=\"sair\" \" href=\"../scripts/logout.php\"> <i class=\"fa fa-sign-out pull-right\"></i> Sair</a></li>
              </ul>
            </li>
          </ul>

        </nav>
      </div>
    </div>

    <!-- FIM MENU TOPO-->
    ";

    echo "<script>
     $('#clienteX').val('".$_SESSION['filtroCliente']."');
     $('#consultorX').val(".$_SESSION['filtroConsultor'].");";

     echo "</script>";

  }
}
?>
