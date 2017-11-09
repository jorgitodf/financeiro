

<div class="container-fluid col-sm-12 col-md-12 col-lg-12">
    <div class="row-fluid">
        <section class="col-md-12 col-lg-12 col-sm-12" id="sec_conta_home">
            <div class="row-fluid">
                <div class="btn-group">
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
                <div class="btn-group">
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
                <div class="btn-group">
                    <button type="button" class="btn btn-success" id="btn_agendamento"><a class="href_btn_home" href="/conta/acessar/<?php echo !empty($idConta) ? $idConta : "" ?>">Cartão Crédito</a></button>
                    <button type="button" class="btn btn-success dropdown-toggle" id="btn_agendamento_1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="/cartaocredito/cadastrar/<?php echo !empty($idConta) ? $idConta : "" ?>">Cadastrar</a></li>
                        <li><a href="#">Lançar Fatura</a></li>
                        <li><a href="/cartaocredito/comprar/<?php echo !empty($idConta) ? $idConta : "" ?>">Lançar Compra</a></li>
                        <li><a href="#">Fechar Fatura</a></li>
                        <li><a href="#">Consultar Fatura</a></li>
                    </ul>
                </div>
            </div>
        </section>
    </div>
</div>
