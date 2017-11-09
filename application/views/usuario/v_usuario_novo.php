
<div class="container col-md-sm col-md-12 col-lg-12">
  <div class="row">
    <section class="col-md-4 col-md-offset-4 col-lg-4 col-lg-offset-4" id="sec_cadastro_usuario">
      <h2>Cadastro de Novo Usuário</h2>

      <form method="POST" action="/usuario" id="form_cadastro_usuario">

        <div class="form-group">
          <label for="nome">Nome:</label>
          <input type="nome" name="nome" id="nome" class="form-control" placeholder="Insira seu Nome Completo aqui..."/>
        </div>

        <div class="form-group">
          <label for="email">E-mail:</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="Insira seu E-mail aqui..."/>
        </div>

        <div class="form-group">
          <label for="password">Senha:</label>
          <input type="password" name="password" id="password" class="form-control" placeholder="Insira sua Senha aqui..."/>
        </div>

        <div class="form-group">
          <label for="repeat_password">Redigite a Senha:</label>
          <input type="password" name="repeat_password" id="repeat_password" class="form-control" placeholder="Digite novamente sua Senha aqui..."/>
        </div>

        <input type="submit" value="Cadastrar" class="btn btn-primary" />

        <div class="retorno"></div>
      </form>
    </section>
  </div>
</div>
