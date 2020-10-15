<html>
<head>
<title> Maps </title>
<meta charset=UTF-8>
</head>
<body>

<!-- Formulário para passar rua e cidade para o mapa-->
<form>

<!-- Componente rua -->
<label> Onde estou: 
<input type="text" name="cep" id="cep" onblur="pesquisacep(this.value);"/>
</label>

<!-- Componente cidade -->
<label> Quero ir para:
<input type="text" name="cidade" id="cidade"/>
</label>

<button type="button" onclick="buscaLocal()"> Buscar </button><input type="text" id="enderecoCep">
</form>

<!-- INCORPORA O MAPA DA GOOGLE -->

<iframe src="https://www.google.com/maps/embed/v1/directions?origin=Polvilho+Cajamar&destination=Carapicuiba+Extra&key=AIzaSyD7UuaO557GPJOFsdw-g59IRGPleJw6N5w" id='mapa' width="100%" height="90%" frameborder="0"  style="border:0">
  </iframe>
<!-- Seta o novo endereco do mapa -->
<script>
function buscaLocal()
{
	var rua = document.getElementById('enderecoCep').value;
	rua = rua.replace(" ","+");
	cidade = cidade.replace(" ","+");
	document.getElementById('mapa').src = "https://www.google.com/maps/embed/v1/place?q="+rua+"&key=AIzaSyD7UuaO557GPJOFsdw-g59IRGPleJw6N5w";
}
</script>

<!-- Busca dados por CEP -->
    <script type="text/javascript" >
    
    function limpa_formulário_cep() {
            //Limpa valores do formulário de cep.
            document.getElementById('enderecoCep').value=("");
    }

    function meu_callback(conteudo) {
        if (!("erro" in conteudo)) {
            //Atualiza os campos com os valores.
            document.getElementById('enderecoCep').value=(conteudo.logradouro);
			document.getElementById('enderecoCep').value +=	" "+(conteudo.bairro);
			
			buscaLocal();
        } //end if.
        else {
            //CEP não Encontrado.
            limpa_formulário_cep();
            alert("CEP não encontrado.");
        }
    }
        
    function pesquisacep(valor) {

        //Nova variável "cep" somente com dígitos.
        var cep = valor.replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                
                //Cria um elemento javascript.
                var script = document.createElement('script');

                //Sincroniza com o callback.
                script.src = '//viacep.com.br/ws/'+ cep + '/json/?callback=meu_callback';

                //Insere script no documento e carrega o conteúdo.
                document.body.appendChild(script);

            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    };

    </script>


</body>
</html>