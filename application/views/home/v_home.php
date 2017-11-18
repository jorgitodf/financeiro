
<div class="container-fluid col-md-sm col-md-12 col-lg-12 div_page_principal">
    <div class="row-fluid">
        <?php if (!empty($conta)): ?>
        <section class="jumbotron col-xs-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3" id="sec_sem_conta">
            <p><?php echo $conta; ?></p>
            <a class="btn btn-primary" href="/conta" role="button">Cadastrar Conta</a>
        </section>
        <?php else: ?>
          <section class="jumbotron col-xs-12 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-3" id="sec_com_conta">
              <h3>Selecione uma Conta Abaixo</h3><br/>
              <div class="row-fluid">
                  <?php if (!empty($contas)): ?>
                      <?php foreach ($contas as $conta): ?>
                          <div class="radio col-xs-12 col-sm-12 col-md-12">
                              <label>
                                  <input type="radio" name="radio_conta" id="radio_conta" checked="checked" value="<?php echo $conta['id_conta']; ?>">
                                  <?php echo "<span class='tipo_conta_home'>{$conta['tipo_conta']}</span> - <span class='num_conta_home'>Número:</span> {$conta['numero_conta']}-{$conta['dig_ver_conta']} - <span class='ag_conta_home'>Agência:</span> {$conta['cod_agencia']} - <span class='nome_banco_home'>Banco:</span> {$conta['nome_banco']}"; ?>
                              </label>
                          </div>
                      <?php endforeach; ?>
                  <?php else: ?>
                      <?php echo ""; ?>
                  <?php endif; ?>
              </div><br/><br/><br/>
              <div class="row-fluid">
                  <a class="btn btn-primary" href="/conta/acessar/<?php echo $contas[0]['id_conta'] ?>">Acessar</a>
              </div>
          </section>
        <?php endif; ?>
    </div>
</div>
