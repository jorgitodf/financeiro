
<div class="container-fluid col-sm-12 col-md-12 col-lg-12">
    <div class="row-fluid">
        <section class="col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3 col-sm-6 col-sm-offset-3 jumbotron" id="sec_fechar_fatura_cartao">
            <form method="POST" action="/cartaocredito/pagar-fatura" id="form_buscar_fatura_fechar" >    
                <div class="panel panel-primary" id="div_panel_fecha_fatura_cartao">
                    <div class="panel-heading">
                        <h3 class="panel-title">Fechar Fatura de Cartão de Crédito</h3>
                    </div>
                    <div class="panel-body" id="panel_body_fatura"> 
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-md-12 col-sm-12 col-lg-12" id="">
                                <label for="id_fatura_cartao" class="control-label">Cartão de Crédito:</label>
                                <select class="form-control input-sm" name="id_fatura_cartao" id="id_fatura_cartao">
                                    <option></option>
                                    <?php foreach ($faturas as $value): ?> 
                                    <option value="<?php echo $value['id']; ?>">Data de Vencimento: <?php echo $value['data']; ?> - <?php echo wordwrap($value['num'], 4, '.', true); ?> - <?php echo $value['bandeira']; ?> - <?php echo $value['nome']; ?></option>
                                    <?php endforeach; ?>
                                </select>  
                            </div>
                        </div>
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-5 col-md-5 col-lg-5" id="">
                                <button type="submit" id="btn_fechar_fatura_buscar" class="btn btn-primary">Buscar</button>
                                <a class="btn btn-primary" href="/conta/acessar/<?php echo $idConta; ?>">Início</a>
                            </div>
                            <div class="form-group form-group-sm retorno col-sm-6 col-md-6 col-lg-6" id="retorno_fechar_fatura_cartao_credito">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>