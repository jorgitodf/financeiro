
<div class="container-fluid col-xs-10 col-sm-10 col-md-10 col-lg-10">
	<div class="row-fluid">
		<section id="sec_ralatorios_index">
			<form method="POST" action="/relatorio/anual" id="form_relatorio_anual">
			    <fieldset class="scheduler-border">
				    <legend class="legend-scheduler-border">Relatório Anual</legend>
				    <div class="form-group form-group-sm col-xs-5 col-sm-5 col-md-5 col-lg-5">
					    <label for="ano">Ano:</label>
					    <select class="form-control input-sm" name="ano" id="ano">
						    <option></option>
						    <option value="2014">2014</option>
						    <option value="2015">2015</option>
						    <option value="2016">2016</option>
						    <option value="2017">2017</option>
					    </select>
                        <input type="hidden" name="token" id="token" value="<?php echo !empty($token) ? $token->token : ''; ?>">
				    </div>
				    <div class="form-group form-group-sm col-xs-3 col-sm-3 col-md-3 col-lg-3"><br/>
					    <button type="submit" id="btn_buscar_rel_anual" class="btn btn-primary">Buscar</button>
				    </div>
                    <div class="form-group form-group-sm col-xs-3 col-sm-3 col-md-3 col-lg-3" id="div_ret_rel_anual">
                        <div class="form-group form-group-sm retorno" id="retorno_relatorio_anual"></div>
				    </div>
			    </fieldset>
			</form>
		</section>
	</div>
</div>
<div class="container-fluid col-xs-10 col-sm-10 col-md-10 col-lg-10" id="div_grafico">
	<canvas class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="grafico"></canvas>
	<script type="text/javascript">
	window.onload = function() {
		var contexto = document.getElementById("grafico").getContext("2d");
		var grafico = new Chart(contexto, {
			type:'line',
			data: {
				labels: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
				datasets: [{
					label:'Gasolina',
					backgroundColor:'red',
					borderColor:'red',
					data: [
						120.00, 300.00, 400.00, 350.00, 550.00, 480.00, 450.00, 800.00, 500.00, 350.00, 650.00, 470.00
					],
					fill:false
				}, {
					label:'Bebidas',
					backgroundColor:'green',
					borderColor:'green',
					data: [
						80.50, 150.00, 350.00, 500.00, 220.00, 700.00, 350.00, 240.00, 80.00, 300.00, 450.00, 350.00
					],
					fill:false
				}],
			},
			options: {
				responsive: true
			}
		});
	}
</script>
</div>


