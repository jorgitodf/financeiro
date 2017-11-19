

<div class="container cor_fundo col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <div class="row">
        <section class="col-xs-12 col-md-4 col-sm-4 col-lg-4" id="sec_login">
            <div class="well box-login" id="div_login">
                <div>
                    <h2 class="ls-login-logo">Login Sistema Financeiro</h2>
                </div>
                <form method="POST" action="/auth/login" id="form_login">
                    <fieldset>
                        <div class="form-group col-xs-12 col-md-12 col-sm-12 col-lg-12">
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-envelope"></span></span>
                                <input type="email" name="email" id="email" class="form-control" aria-describedby="sizing-addon2" placeholder="Insira seu email..."/>
                            </div>    
                        </div>
                        <div class="form-group col-xs-12 col-md-12 col-sm-12 col-lg-12">
                            <div class="input-group">
                                <span class="input-group-addon" id="sizing-addon2"><span class="glyphicon glyphicon-lock"></span></span>
                                <input type="password" name="password" id="password" class="form-control" aria-describedby="sizing-addon2" placeholder="Insira sua senha..."/>
                            </div>
                        </div>  
                        <div class="form-group col-xs-12 col-md-12 col-sm-12 col-lg-12" id="">
                            <input type="submit" value="Acessar" class="btn btn-primary btn-tam" />
                            <a class="btn btn-primary btn-tam" id="btn_cadastro" href="/usuario" title="Cadastrar">Cadastrar</a>
                        </div>
                        <div class="form-group col-xs-12 col-md-12 col-sm-12 col-lg-12 retorno" id=""></div>
                    </fieldset>    
                </form>
            </div>    
        </section>
    </div>
</div>

