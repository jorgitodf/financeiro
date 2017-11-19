
<div class="container cor_fundo col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <section class="col-xs-11 col-md-5 col-sm-5 col-lg-5" id="sec_cadastro_usuario">
            <div class="well box-login" id="div_cad_usuario">
                <div>
                    <h2 id="header_cad_usuario">Cadastro de Novo Usuário</h2>
                </div>
                <form method="POST" action="/usuario" id="form_cadastro_usuario">
                <fieldset>
                    <div class="form-group col-xs-12 col-md-12 col-sm-12 col-lg-12">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-pencil"></span></span>
                            <input type="text" name="nome" id="nome" class="form-control" aria-describedby="sizing-addon2" placeholder="Insira seu Nome"/>
                        </div>    
                    </div>
                    <div class="form-group col-xs-12 col-md-12 col-sm-12 col-lg-12">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-envelope"></span></span>
                            <input type="email" name="email" id="email" class="form-control" aria-describedby="sizing-addon2" placeholder="Insira seu E-mail"/>
                        </div>    
                    </div>
                    <div class="form-group col-xs-12 col-md-12 col-sm-12 col-lg-12">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" name="password" id="password" class="form-control" aria-describedby="sizing-addon2" placeholder="Insira sua Senha"/>
                        </div>
                    </div>  
                    <div class="form-group col-xs-12 col-md-12 col-sm-12 col-lg-12">
                        <div class="input-group">
                            <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" name="repeat_password" id="repeat_password" class="form-control" aria-describedby="sizing-addon2" placeholder="Digite novamente sua sua Senha"/>
                        </div>
                    </div> 
                    <div class="form-group col-xs-12 col-md-12 col-sm-12 col-lg-12">
                        <div class="form-group" id="retorno_cad_usuario">
                            <input type="submit" value="Cadastrar" class="btn btn-primary" id="btn_cadastrar"/>
                            <a class="btn btn-primary" id="btn_inicio" href="/home" title="Início">Início</a>
                        </div> 
                        <div class="form-group col-xs-12 col-md-8 col-sm-8 col-lg-8 retorno" id=""> 
                        </div>  
                    </div>
                </fieldset>
                </form>
            </div>
        </section>
    </div>
</div>
