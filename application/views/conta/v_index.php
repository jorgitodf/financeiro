
<div class="container col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <section class="col-md-7 col-md-offset-2 col-lg-7 col-lg-offset-2" id="sec_cadastro_conta">
          <form method="POST" action="/conta/cadastrar" id="form_cadastro_conta" >
              <div class="panel panel-info" id="panel_cadastro">
                  <div class="panel-heading" id="panel_heading_cadastro">
                      <h3 class="panel-title">Cadastro de Conta</h3>
                  </div>
                  <div class="panel-body" id="panel_body_cadastro_conta">
                      <div class="row-fluid">
                          <div class="form-group form-group-sm col-sm-6 col-md-6">
                              <label for="nome_banco" class="control-label">Nome Banco</label>
                              <select class="form-control input-sm" autofocus="autofocus" name="nome_banco" id="nome_banco" >
                                  <option></option>
                                  <?php foreach ($bancos as $banco): ?>
                                      <option value="<?php echo $banco['cod_banco']; ?>"><?php echo $banco['nome_banco']; ?> </option>
                                  <?php endforeach; ?>
                              </select>
                          </div>
                          <div class="form-group form-group-sm col-sm-3 col-md-3">
                              <label for="cod_agencia" class="control-label">Código da Agência</label>
                              <input type="text" name="cod_agencia" id="cod_agencia" class="form-control input-sm"/>
                          </div>
                          <div class="form-group form-group-sm col-sm-3 col-md-3">
                              <label for="dig_agencia" class="control-label">Dígito Verificador Ag.</label>
                              <input type="text" name="dig_agencia" id="dig_agencia" class="form-control input-sm"/>
                          </div>
                      </div>
                      <div class="row-fluid">
                          <div class="form-group form-group-sm col-sm-3 col-md-3">
                              <label for="num_conta" class="control-label">Número da Conta</label>
                              <input type="text" name="num_conta" id="num_conta" class="form-control input-sm"/>
                          </div>
                          <div class="form-group form-group-sm col-sm-3 col-md-3">
                              <label for="dig_conta" class="control-label">Dígito Verificador Conta</label>
                              <input type="text" name="dig_conta" id="dig_conta" class="form-control input-sm"/>
                          </div>
                          <div class="form-group form-group-sm col-sm-3 col-md-3">
                              <label for="cod_operacao" class="control-label">Código da Operação</label>
                              <input type="text" name="cod_operacao" id="cod_operacao" class="form-control input-sm"/>
                          </div>
                          <div class="form-group form-group-sm col-sm-3 col-md-3">
                              <label for="tipo_conta" class="control-label">Tipo de Conta</label>
                              <select class="form-control input-sm" name="tipo_conta" id="tipo_conta" >
                                  <option></option>
                                  <?php foreach ($tipoConta as $tipo): ?>
                                      <option value="<?php echo $tipo['id_tipo_conta']; ?>"><?php echo $tipo['tipo_conta']; ?> </option>
                                  <?php endforeach; ?>
                              </select>
                          </div>
                      </div>
                      <div class="row-fluid">
                          <div class="form-group form-group-sm col-sm-4 col-md-4">
                              <button type="submit" class="btn btn-primary">Cadastrar</button>
                          </div>
                          <div class="form-group form-group-sm col-sm-5 col-md-5 retorno">

                          </div>
                      </div>
                  </div>
              </div>
          </form>
        </section>
    </div>
</div>
