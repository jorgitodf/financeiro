
<div class="container-fluid col-xs-9 col-sm-9 col-md-9 col-lg-9">
	<div class="row-fluid">
		<section id="sec_ralatorios_index">
			<form method="POST" action="/relatorio/anual" id="form_relatorio_anual">
			    <fieldset class="scheduler-border">
					<legend class="legend-scheduler-border">Relatório Anual</legend>
					<div class="form-group form-group-sm col-xs-4 col-sm-4 col-md-4 col-lg-4">
					    <label for="categoria">Categoria:</label>
					    <select class="form-control input-sm" name="categoria" id="categoria">
							<option></option>
							<?php foreach ($categorias as $categoria): ?>
							<option value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['nome_categoria']; ?> </option>
							<?php endforeach; ?>
					    </select>
                        <input type="hidden" name="token" id="token" value="<?php echo !empty($token) ? $token->token : ''; ?>">
				    </div>
				    <div class="form-group form-group-sm col-xs-4 col-sm-4 col-md-4 col-lg-4">
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
</div>


