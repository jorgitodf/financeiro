
<div class="container-fluid col-sm-12 col-md-12 col-lg-12">
    <div class="row-fluid">
        <section class="col-md-8 col-lg-8 col-sm-8 jumbotron" id="sec_pagar_fatura_cartao">
            <form method="POST" action="/cartaocredito/fatura-pagar" id="form_pagar_fatura" >    
                <div class="panel panel-primary" id="div_panel_fecha_fatura_cartao">
                    <div class="panel-heading">
                        <h3 class="panel-title">Pagamento de Fatura de Cartão de Crédito</h3>
                    </div>
                    <div class="panel-body" id="panel_body_fatura"> 

                        <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                            <label for="num_cartao" class="control-label muda_label">Número do Cartão:</label>
                            <input type="text" name="num_cartao" id="num_cartao" class="form-control input-sm" readonly="true" value="<?php echo $fatura->num; ?>"/>
                            <input type="hidden" name="id_cartao_fat" id="id_cartao_fat" value="<?php echo $fatura->id; ?>"/>
                            <input type="hidden" name="id_cartao_cre" id="id_cartao_cre" value="<?php echo $fatura->idCartao; ?>"/>
                            <input type="hidden" name="" id="action" value="/cartaocredito/getRestanteFaturaAnterior"/>
                        </div>
                        <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                            <label for="nome_banco" class="control-label muda_label">Banco:</label>
                            <input type="text" name="nome_banco" id="nome_banco" class="form-control input-sm" readonly="true" value="<?php echo $fatura->nome; ?>"/>
                        </div>
                        <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                            <label for="nome_bandeira" class="control-label muda_label">Bandeira:</label>
                            <input type="text" name="nome_bandeira" id="nome_bandeira" class="form-control input-sm" readonly="true" value="<?php echo $fatura->bandeira; ?>"/>
                        </div>
                        <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                            <label for="dt_vencimento" class="control-label muda_label">Data de Vencimento:</label>
                            <input type="text" name="dt_vencimento" id="dt_vencimento" class="form-control input-sm" readonly="true" value="<?php echo $fatura->data; ?>"/>
                        </div>

                        <div class="row-fluid" id="div_row_table_fecha_fatura">    
                            <?php if (isset($itensfatura) && !empty($itensfatura)): ?>
                                <table class="table table-hover table-condensed table-responsive" id="table_lista_itens_fatura">
                                    <thead>
                                        <tr>
                                            <th class="alinha_th_centro muda_label" width="15%">Data da Compra</th>
                                            <th class="alinha_th_centro muda_label" width="40%">Descrição</th>
                                            <th class="alinha_th_centro muda_label" width="15%">Parcela</th>
                                            <th class="alinha_th_centro muda_label" width="10%">Valor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $total = 0 ?>
                                        <?php foreach ($itensfatura as $value): ?>
                                        <tr>
                                            <td class="alinha_td_centro"><?php echo $value['data_compra']; ?></td>
                                            <td class=""><?php echo $value['despesa']; ?></td>
                                            <td class="alinha_td_centro"><?php echo $value['parcela']; ?></td>
                                            <td class="">R$ <span class="alinha_td_direita"><?php echo number_format($value['valor_compra'], 2, ',', '.'); ?></span></td>
                                        </tr>
                                        <?php $total += $value['valor_compra']; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot> 
                                        <tr>
                                            <td class="muda_label alinha_td_centro">SubTotal</td>
                                            <td class="muda_label"></td>
                                            <td class="muda_label"></td>
                                            <td class="muda_label ">R$ <?php echo number_format($total, 2, ',', '.'); ?></td>
                                            <input type="hidden" name="subtotal" id="subtotal" value="<?php echo $total; ?>"/>
                                        </tr>    
                                    </tfoot>    
                                </table>
                            <?php else: ?>
                                    <div class="form-group form-group-sm col-sm-12 col-md-12 col-lg-12" id="">
                                        <div class="alert alert-warning" id="msg_alerta_sem_despesa_fatura">Não há nenhuma despesa lançada para essa Fatura no Momento.</div>
                                    </div>    
                            <?php endif; ?>
                        </div>  
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                                <label for="encargos" class="control-label muda_label">Encargos:</label>
                                <input type="text" name="encargos" id="encargos" class="form-control input-sm" value="<?php $r='R$ '; echo isset($rettotalgeral['encargos'])&&!empty($rettotalgeral['encargos']) ? $r.number_format($rettotalgeral['encargos'], 2, ',', '.') : ""; ?>"/>
                            </div>
                            <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                                <label for="iof" class="control-label muda_label">IOF:</label>
                                <input type="text" name="iof" id="iof" class="form-control input-sm" value="<?php $r='R$ '; echo isset($rettotalgeral['iof'])&&!empty($rettotalgeral['iof']) ? $r.number_format($rettotalgeral['iof'], 2, ',', '.') : ""; ?>"/>
                            </div>
                            <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                                <label for="anuidade" class="control-label muda_label">Anuidade:</label>
                                <input type="text" name="anuidade" id="anuidade" class="form-control input-sm" value="<?php $r='R$ '; echo isset($rettotalgeral['anuidade'])&&!empty($rettotalgeral['anuidade']) ? $r.number_format($rettotalgeral['anuidade'], 2, ',', '.') : ""; ?>"/>
                            </div>
                            <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                                <label for="protecao_prem" class="control-label muda_label">Proteção Premiada:</label>
                                <input type="text" name="protecao_prem" id="protecao_prem" class="form-control input-sm" value="<?php $r='R$ '; echo isset($rettotalgeral['protecao'])&&!empty($rettotalgeral['protecao']) ? $r.number_format($rettotalgeral['protecao'], 2, ',', '.') : ""; ?>"/>
                            </div>  
                        </div>

                            <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                                <label for="juros_fat" class="control-label muda_label">Juros:</label>
                                <input type="text" name="juros_fat" id="juros_fat" class="form-control input-sm" value="<?php $r='R$ '; echo isset($rettotalgeral['juros'])&&!empty($rettotalgeral['juros']) ? $r.number_format($rettotalgeral['juros'], 2, ',', '.') : ""; ?>"/>
                            </div>
                            <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                                <label for="restante" class="control-label muda_label">Restante Fatura Anterior:</label>
                                <input type="text" name="restante" id="restante" class="form-control input-sm" value="<?php $r='R$ '; echo isset($rettotalgeral['restante'])&&!empty($rettotalgeral['restante']) ? $r.number_format($rettotalgeral['restante'], 2, ',', '.') : ""; ?>"/>
                            </div>
                            <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                                <label for="valor_total" class="control-label muda_label">Valor Total Fatura:</label>
                                <input type="text" name="valor_total" id="valor_total" class="form-control input-sm" value="<?php $r='R$ '; echo isset($rettotalgeral['totalgeral'])&&!empty($rettotalgeral['totalgeral']) ? $r.number_format($rettotalgeral['totalgeral'], 2, ',', '.') : ""; ?>"/>
                            </div>
                            <div class="form-group form-group-sm col-sm-3 col-md-3 col-lg-3" id="">
                                <label for="valor_pagar" class="control-label muda_label">Valor a Pagar:</label>
                                <input type="text" name="valor_pagar" id="valor_pagar" class="form-control input-sm" value="<?php $r='R$ '; echo isset($rettotalgeral['valor_pagar'])&&!empty($rettotalgeral['valor_pagar']) ? $r.number_format($rettotalgeral['valor_pagar'], 2, ',', '.') : ""; ?>"/>
                                <input type="hidden" name="set_vlr_pagar" value="pagar"/>
                            </div>
                            
                        <div class="row-fluid">
                            <div class="form-group form-group-sm col-sm-6 col-md-6 col-lg-6" id="div_botoes_fecha_fatura">
                                <button type="button" id="btn_novo_pgto_fatura" class="btn btn-primary">Novo</button>
                                <button type="button" id="btn_calcular_fatura" class="btn btn-primary" disabled="disabled">Calcular</button>
                                <button type="submit" id="btn_pagar_fatura" class="btn btn-primary" disabled="disabled">Pagar</button>
                                <button type="button" id="btn_limpar_pgto_fatura" class="btn btn-primary" disabled="disabled">Limpar</button>
                                <a class="btn btn-primary" href="/conta/acessar/<?php echo $idConta; ?>">Início</a>
                            </div>
                            <div class="form-group form-group-sm retorno col-sm-6 col-md-6 col-lg-6" id="retorno_pagar_fatura_cartao_credito">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </div>
</div>