
<div class="container col-md-sm col-md-12 col-lg-12">
  <div class="row">
    <section class="col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4" id="sec_login">
      <h2>Login Acesso ao Sistema</h2>

      <form method="POST" action="/auth/login" id="form_login">

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Insira seu email aqui..."/>
        </div>

        <div class="form-group">
          <label for="password">Senha</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Insira sua senha aqui..."/>
        </div>

        <input type="submit" value="Acessar" class="btn btn-primary" />
        <a class="btn btn-primary" id="btn_cadastro" href="/usuario" title="Cadastrar">Cadastrar</a>

        <div class="retorno"></div>
      </form>
    </section>
  </div>
</div>
