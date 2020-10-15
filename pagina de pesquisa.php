
<!-- MUDAR -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- TÍTULO DO MODAL -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">Podemos concluir?</h4>
			</div>
			<!-- CONTEÚDO DO MODAL -->
			<div class="modal-body">
				<!-- MUDAR SOMENTE O ACTION DO FORM-->
				<form method=post action="../scripts/fecharOcorrencia.php">
					<label>Avalie o atendimento e feche a ocorrência</label>
					<!-- componente qualidade -->
					<div class="form-group" align="center">
						<h4>Qualidade do atendimento:<small> (1 a 5 estrelas)</small></h4>
						<div>
							<div class="starrr stars"></div>
							Sua avaliação é de  <span class="stars-count" id="atendimento" name="atendimento">0</span> estrela(s)
						</div>
						<input type="text" id="atendimentoAv" name="atendimentoAv" hidden>
					</div>

					<!-- componente prazo -->
					<div class="form-group" align="center">
						<h4>Atendimento no prazo:<small> (1 a 5 estrelas)</small></h4>
						<div>
							<div class="starrr stars2"></div>
							Sua avaliação é de  <span class="stars-count2">0</span> estrela(s)
						</div>
						<input type="text" id="prazoAv" name="prazoAv" hidden>
					</div>

					<!-- componente expectativas -->
					<div class="form-group" align="center">
						<h4>Supriu suas expectativas?<small> (1 a 5 estrelas)</small></h4>
						<div>
							<div class="starrr stars3"></div>
							Sua avaliação é de  <span class="stars-count3" id="expectativas">0</span> estrela(s)
						</div>
						<input type="text" id="expectativasAv" name="expectativasAv" hidden>
					</div>
					<!-- COMPONENTE MELHORIAS -->  
					<div class="form-group" align="center">
						<label>Sugestões ou reclamações?
						</label>
						<textarea style="resize:none" id="sugestoes" placeholder="Deixe suas sugestões" class="form-control" name="sugestoes" data-parsley-trigger="keyup" data-parsley-minlength="0" maxLength="20" data-parsley-minlength-message="Pelo menos uma palavrinha por favor!"
						data-parsley-validation-threshold="10"></textarea>
					</div>
					<!-- OCORR END-->
					<input type="text" id="idOcorrEnd" name="idOcorrEnd" hidden>

					<!-- LINHA -->
					<div class="ln_solid"></div>
					<!-- BOTÃO SUBMISSÃO -->
					<div class="form-group">
						<div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
							<button type="submit" class="btn btn-success" >Concluir</button>
							<button type="button" class="btn btn-danger" data-dismiss="modal" onclick="limparIdEnd()">Cancelar</button>
							<div class="ln-solid"></div>
						</div>
					</div>		
				</form>
			</div>
			<!-- FIM CONTEÚDO DO MODAL -->
		</div>
	</div>
</div>


<script>
	function limparIdEnd()
	{
		document.getElementById('idOcorrEnd').value = 0;
	}
</script>
<script>
	function setIdEnd(id)
	{
		document.getElementById('idOcorrEnd').value = id;
	}
</script>

<!-- FIM DO MODAL -->