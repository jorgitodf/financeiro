


<div class="container-fluid col-sm-12 col-md-12 col-lg-12">
    <div class="row-fluid">
        <section class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-6 col-sm-offset-3 jumbotron" id="sec_alterar_pagamento_agendado">
            <form method="POST" action="/agendamento/alterar" id="form_editar_agendamento_debito" >
                <div class="panel panel-primary" id="div_panel_form_agendar_pagamento">
                    <div class="panel-heading">
                        <h3 class="panel-title">Alterar Pagamento Agendado</h3>
                    </div>
                    <div class="panel-body">	
                        <div class="row-fluid">
                            <?php isset($pagamento) ? $pagamento : ""; ?>
                            <div class="form-group form-group-sm col-sm-5 col-md-5 col-lg-5">
                                <label for="data_pgto" class="control-label">Data:</label>
                                <input type="date" name="data_pgto" id="data_pgto" disabled="disabled" class="form-control input-sm" value="<?php echo $pagamento[0]['data_pagamento']; ?>"/>
                            </div> 
                            <div class="form-group form-group-sm col-sm-7 col-md-7 col-lg-7">
                                <label for="mov_pgto" class="control-label">Movimentação:</label>
                                <input type="text" name="mov_pgto" id="mov_pgto" disabled="disabled" class="form-control input-sm" value="<?php echo $pagamento[0]['movimentacao']; ?>"/>
                            </div>
                            <input type="hidden" name="idConta" value="<?php echo $idConta; ?>"/>
                            <input type="hidden" name="idPgtoAgendado" value="<?php echo $pagamento[0]['id_pgto_agendado']; ?>"/>
                        </div>
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-7 col-md-7 col-lg-7">
                                <label for="categoria_pgto" class="control-label">Categoria:</label>
                                <select class="form-control input-sm" name="categoria_pgto" id="categoria_pgto" disabled="disabled">
                                    <?php foreach ($categorias as $categoria): ?> 
                                        <option <?php echo ($categoria['id_categoria']==$pagamento[0]['fk_id_categoria'] ? 'selected="selected"':'') ?> value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['nome_categoria']; ?> </option>
                                    <?php endforeach; ?>
                                </select>  
                            </div>
                            <div class="form-group form-group-sm col-sm-5 col-md-5 col-lg-5">
                                <label for="valor_pgto" class="control-label">Valor:</label>
                                <input type="text" name="valor_pgto" id="valor_pgto" disabled="disabled" class="form-control input-sm" value="<?php echo number_format($pagamento[0]['valor'], 2, ',', '.'); ?>"/>
                            </div> 
                        </div>
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-5 col-md-5 col-lg-5" id="div_btn_agendar">
                                <button type="button" id="btn_editar_pgto_agendado" class="btn btn-primary">Editar</button>
                                <button type="submit" id="btn_salvar_pgto_agendado" class="btn btn-primary" disabled="disabled">Salvar</button>
                                <a class="btn btn-primary" href="/agendamento/listar/<?php echo $idConta; ?>" title="Listar">Listar</a>
                            </div>
                            <div class="form-group form-group-sm retorno col-sm-7 col-md-7" id="retorno_alterar_pgto_agendado">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>