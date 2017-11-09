

<div class="container-fluid col-sm-12 col-md-12 col-lg-12">
    <div class="row-fluid">
        <section class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-6 col-sm-offset-3 jumbotron" id="sec_cartao_lancar_compra">
            <form method="POST" action="/cartaocredito/comprar" id="form_deb_fatura_cartao_credito" >    
                <div class="panel panel-primary" id="div_panel_form_deb_fatura_carta_credito">
                    <div class="panel-heading">
                        <h3 class="panel-title">Lançamento de Compra em Fatura de Cartão de Crédito</h3>
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
                            <div class="form-group form-group-sm col-sm-5 col-md-5 col-lg-5" id="">
                                <label for="data_compra" class="control-label">Data da Compra:</label>
                                <input type="date" name="data_compra" id="data_compra" class="form-control input-sm" disabled/>
                            </div>
                            <div class="form-group form-group-sm col-sm-7 col-md-7 col-lg-7" id="">
                                <label for="descricao" class="control-label">Descrição:</label>
                                <input type="text" name="descricao" id="descricao" class="form-control input-sm" disabled/>
                            </div>
                        </div> 
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-5 col-md-5 col-lg-5" id="">
                                <label for="valor_compra" class="control-label">Valor da Compra:</label>
                                <input type="text" name="valor_compra" id="valor_compra" class="form-control input-sm" disabled/>
                            </div>
                            <div class="form-group form-group-sm col-sm-7 col-md-7 col-lg-7" id="">
                                <label for="parcela" class="control-label">Parcela:</label>
                                <select class="form-control input-sm" name="parcela" id="parcela" disabled="disabled">
                                    <option></option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>  
                            </div>
                            <input type="hidden" name="idUser" id="idUser" value="<?php echo $idUser; ?>">
                        </div>
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-5 col-md-5 col-lg-5" id="">
                                <button type="button" id="btn_nova_lanc_fatura" class="btn btn-primary">Novo</button>
                                <button type="submit" id="btn_salvar_novo_lanc_fatura" class="btn btn-primary" disabled>Salvar</button>
                                <a class="btn btn-primary" href="/conta/acessar/<?php echo $idConta; ?>">Início</a>
                            </div>
                            <div class="form-group form-group-sm retorno col-sm-6 col-md-6 col-lg-6" id="retorno_lancar_compra_cartao_credito">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>