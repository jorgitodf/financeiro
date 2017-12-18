
<div class="container-fluid col-xs-12 col-sm-12 col-md-12 col-lg-12">
	<div class="row-fluid">
		<section class="col-xs-6 col-sm-6 col-md-6 col-lg-6" id="sec_relatorios_index">
			<form method="POST" action="/relatorio/anual" id="form_relatorio_anual">
				<div class="panel panel-primary" id="div_panel_form_debitar">
                    <div class="panel-heading">
                        <h3 class="panel-title">Relatório Anual</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <label for="categoria">Categoria:</label>
                                <select class="form-control input-sm" name="categoria" id="categoria">
                                    <option></option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['nome_categoria']; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                                <input type="hidden" name="token" id="token" value="<?php echo !empty($token) ? $token->token : ''; ?>">
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-xs-6 col-sm-6 col-md-6" id="">
                                <button type="submit" id="btn_buscar_rel_anual" class="btn btn-primary">Buscar</button>
                            </div>
                            <div class="form-group form-group-sm col-xs-5 col-sm-5 col-md-5 retorno" id="retorno_relatorio_anual">
                            </div>
                        </div>
                    </div>
                </div>
			</form>
		</section>
	</div>
    <div class="container-fluid col-xs-12 col-sm-12 col-md-12 col-lg-12" id="div_grafico">
        <canvas class="col-xs-12 col-sm-12 col-md-12 col-lg-12" id="grafico"></canvas>
        <div id="botao" class="col-xs-12 col-sm-12 col-md-12 col-lg-12"></div>
    </div>
</div>



