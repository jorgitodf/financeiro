
<div class="container-fluid col-sm-12 col-md-12 col-lg-12">
    <div class="row-fluid">
        <section class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-6 col-sm-offset-3 jumbotron" id="sec_criar_fatura_cartao">
            <form method="POST" action="/cartaocredito/faturar" id="form_cadastro_fatura_cartao_credito" >    
                <div class="panel panel-primary" id="div_panel_form_criar_fatura_cartao_credito">
                    <div class="panel-heading">
                        <h3 class="panel-title">Nova Fatura de Cartão de Crédito</h3>
                    </div>
                    <div class="panel-body" id="panel_body_fatura"> 
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-md-12 col-sm-12 col-lg-12" id="">
                                <label for="cartao" class="control-label">Cartão de Crédito:</label>
                                <select class="form-control input-sm" name="cartao" id="cartao" disabled="disabled">
                                    <option></option>
                                    <?php foreach ($cartoes as $value): ?> 
                                        <option value="<?php echo $value['id_cartao']; ?>"><?php echo wordwrap($value['numero'], 4, '.', true); ?> - <?php echo $value['bandeira']; ?> - <?php echo $value['nome_banco']; ?></option>
                                    <?php endforeach; ?>
                                </select>  
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-6 col-md-6 col-lg-6" id="">
                                <label for="data_vencimento" class="control-label">Data de Vencimento:</label>
                                <input type="date" name="data_vencimento" id="data_vencimento" class="form-control input-sm" disabled/>
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-5 col-md-5 col-lg-5" id="div_botoes_criar_fatura">
                                <button type="button" id="btn_nova_fatura" class="btn btn-primary">Novo</button>
                                <button type="submit" id="btn_salvar_nova_fatura" class="btn btn-primary" disabled>Salvar</button>
                                <a class="btn btn-primary" href="/conta/acessar/<?php echo $idConta; ?>">Início</a>
                            </div>
                            <div class="form-group form-group-sm retorno col-sm-6 col-md-6 col-lg-6" id="retorno_criar_fatura_cartao_credito">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>