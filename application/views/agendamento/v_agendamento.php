
<div class="container-fluid col-sm-12 col-md-12 col-lg-12">
    <div class="row-fluid">
        <section class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-6 col-sm-offset-3 jumbotron" id="sec_agendar_pagamento">
            <form method="POST" action="/agendamento/agendar" id="form_cad_agendamento_debito">
                <div class="panel panel-primary" id="div_panel_form_debitar">
                    <div class="panel-heading">
                        <h3 class="panel-title">Agendamento de Pagamento</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-5 col-md-5" id="">
                                <label for="data_pgto" class="control-label">Data:</label>
                                <input type="date" name="data_pgto" id="data_pgto" disabled="disabled" class="form-control input-sm" />
                            </div>
                            <div class="form-group form-group-sm col-sm-7 col-md-7" id="">
                                <label for="movimentacao" class="control-label">Movimentação:</label>
                                <input type="text" name="movimentacao" id="movimentacao" disabled="disabled" class="form-control input-sm" />
                                <input type="hidden" name="token" id="token" class="form-control input-sm" value="<?php echo $token->token; ?>"/>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-6 col-md-6" id="">
                                <label for="nome_categoria" class="control-label">Categoria:</label>
                                <select class="form-control input-sm" name="nome_categoria" disabled="disabled" id="nome_categoria" >
                                    <option></option>
                                    <?php foreach ($categorias as $categoria): ?>
                                        <option value="<?php echo $categoria['id_categoria']; ?>"><?php echo $categoria['nome_categoria']; ?> </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group form-group-sm col-sm-6 col-md-6" id="">
                                <label for="valor" class="control-label">Valor:</label>
                                <input type="text" name="valor" id="valor" class="form-control input-sm" disabled="disabled"/>
                                <input type="hidden" name="idConta" id="idConta" value="<?php echo $idConta; ?>"/>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-5 col-md-5">
                                <button type="button" id="btn_novo_agendamento" class="btn btn-primary">Novo</button>
                                <button type="submit" id="btn_salvar_agendamento" class="btn btn-primary" disabled="disabled">Agendar</button>
                                <a class="btn btn-primary" href="/conta/acessar/<?php echo $idConta; ?>">Início</a>
                            </div>
                            <div class="form-group form-group-sm retorno col-sm-7 col-md-7" id="retorno_agendamento_pgto">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>