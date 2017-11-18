
<div class="container-fluid col-xs-12 col-sm-12 col-md-12 col-lg-12 div_page_principal">
    <div class="row-fluid">
        <section class="col-xs-12 col-md-6 col-lg-6 col-sm-6" id="sec_conta_home">
            <div class="row-fluid">
                <div class="btn-group asd">
                    <button type="button" class="btn btn-success btn_extrato" id=""><a class="href_btn_home" href="/conta/acessar/<?php echo !empty($idConta) ? $idConta : "" ?>">Extratos</a></button>
                    <button type="button" class="btn btn-success dropdown-toggle btn_extrato_1" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="/conta/extrato/<?php echo !empty($idConta) ? $idConta : "" ?>">Extrato Mês Atual</a></li>
                        <li><a href="/extrato">Extrato Por Período</a></li>
                    </ul>
                </div>
                <div class="btn-group asd">
                    <button type="button" class="btn btn-success btn_extrato" id=""><a class="href_btn_home" href="/conta/acessar/<?php echo !empty($idConta) ? $idConta : "" ?>">Transações</a></button>
                    <button type="button" class="btn btn-success dropdown-toggle btn_extrato_1" id="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="/conta/debitar/<?php echo !empty($idConta) ? $idConta : "" ?>">Debitar</a></li>
                        <li><a href="/conta/creditar/<?php echo !empty($idConta) ? $idConta : "" ?>">Creditar</a></li>
                    </ul>
                </div>
                <div class="btn-group asd">
                    <button type="button" class="btn btn-success btn_extrato"><a class="href_btn_home" href="/conta/acessar/<?php echo !empty($idConta) ? $idConta : "" ?>">Cartão Crédito</a></button>
                    <button type="button" class="btn btn-success dropdown-toggle btn_extrato_1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="/cartaocredito/cadastrar/<?php echo !empty($idConta) ? $idConta : "" ?>">Cadastrar</a></li>
                        <li><a href="/cartaocredito/comprar/<?php echo !empty($idConta) ? $idConta : "" ?>">Lançar Compra</a></li>
                        <li><a href="/cartaocredito/faturar/<?php echo !empty($idConta) ? $idConta : "" ?>">Nova Fatura</a></li>
                        <li><a href="/cartaocredito/fechar-fatura/<?php echo !empty($idConta) ? $idConta : "" ?>">Fechar Fatura</a></li>
                        <li><a href="#">Consultar Fatura</a></li>
                    </ul>
                </div>
                <div class="btn-group asd">
                    <button type="button" class="btn btn-success btn_extrato" ><a class="href_btn_home" href="/conta/acessar/<?php echo !empty($idConta) ? $idConta : "" ?>">Agendamentos</a></button>
                    <button type="button" class="btn btn-success dropdown-toggle btn_extrato_1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="/agendamento/agendar/<?php echo !empty($idConta) ? $idConta : "" ?>">Agendar Pagamento</a></li>
                        <li><a href="/agendamento/listar">Listagem Pagamentos</a></li>
                    </ul>
                </div>
                <input type="hidden" id="idConta" value="<?php echo !empty($idConta) ? $idConta : ""; ?>">
            </div>
        </section>
        <section class="col-xs-12 col-md-6 col-lg-6 col-sm-6" id="sec_conta_home_tab_pgtos_agendados">
            <div class="row-fluid">
                <div class="table" id="tabela_pgto_agendado">
                </div>  
            </div>
        </section>
    </div>
    <div class="row-fluid">
        <section class="col-xs-12 col-md-6 col-lg-6 col-sm-6" id=""></section>
    </div>
    <div class="row-fluid">
        <section class="col-xs-12 col-md-6 col-lg-6 col-sm-6 retorno" id="">
            
        </section>
    </div>
</div>

<script type="text/javascript">  
    jQuery(function($) {
        var idConta = $("#idConta").val();
        $.ajax({
            type: "POST",
            url: '/conta/montarTabela',
            data: {idConta: idConta},
            dataType: 'json',
            success: function (retorno) {
                if (retorno[0]['status'] == 'success' ) {
                    $('#tabela_pgto_agendado').html(retorno[0]['tabela']);
                } else {
                    alert(retorno);
                }
            },
            fail: function(){
                alert('ERRO: Falha ao carregar o script.');
            }
        });
    });  

    //VERIFICA SE HÁ PAGAMENTO AGENDADO A SER PAGO NA PRESETE DATA E REALIZA O PAGAMENTO
    jQuery(function($) {
        var idContaPg = $("#idConta").val();
        setTimeout(function() {
            $.ajax({
                type: "POST",
                url: '/conta/pagar',
                data: {idConta: idContaPg},
                dataType: 'json',
                success: function (retorno) {
                    if (retorno[0]['status'] == 'error' ) {
                        $('.retorno').html('<div class="alert alert-danger text-center" role="alert" msgError id="msg_ret_sem_pgto">' + retorno[0]['message'] + '</div>');
                        setTimeout(function() {
                            $('#msg_ret_sem_pgto').remove();
                        }, 6000); 
                    } else if (retorno[0]['status'] == 'success' ) {
                        $('#tabela_pgto_agendado').html(retorno[0].tabela);
                        getSaldo();
                        $('.retorno').html('<div class="alert alert-success text-center" role="alert" msgSuccess id="msg_ret_com_pgto">' + retorno[0]['message'] + '</div>');
                        setTimeout(function() {
                            $('#msg_ret_com_pgto').remove();
                        }, 6000);
                    } else {
                        alert(retorno);
                    }
                },
                fail: function(){
                    alert('ERRO: Falha ao carregar o script.');
                }
            });   
        }, 5000); 
    });  
</script>
