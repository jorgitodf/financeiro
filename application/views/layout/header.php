<!doctype html>
<html lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title><?php echo isset($title) ? $title : ""; ?></title>
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css') ?>" media="all" />
        <link rel="stylesheet" href="<?php echo base_url('assets/css/template.css') ?>" media="all" />
        <link rel="stylesheet" href="<?php echo base_url('assets/fonts/glyphicons-halflings-regular.ttf') ?>" media="all" />
        <script src="<?php echo base_url('assets/js/jquery-3.2.1.min.js') ?>"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
        <header id="cabecalho">
            <?php if (!empty($this->session->userdata('user'))) { ?>
            <nav class="navbar navbar-default navbar-fixed-top" id="barra_nav">
                <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav navbar-left" id="navbar-nav">
                            <li class="dropdown">
                                <a class="color_fonte_white" href="" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Minha Conta<span class="caret"></span></a>
                                <ul class="dropdown-menu" id="dropdown-menu">
                                    <li><a href="/home">Página Inicial</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav navbar-nav navbar-form" id="navbar_saldo">
                            <?php if (!empty($conta)): ?>
                            <li>Conta: <span class="info_saldo"><?php echo !empty($conta->numero_conta) ? $conta->numero_conta : ""; ?></span></li><br/>
                            <li>Banco: <span class="info_saldo"><?php echo !empty($conta->nome_banco) ? $conta->nome_banco : ""; ?></span></li><br/>
                            <li>Saldo: <span class="info_saldo" id="saldo_nav"><?php echo !empty($conta->saldo) ? number_format($conta->saldo, 2, ',', '.') : ""; ?></span></li><br/>
                            <?php endif; ?>
                        </ul>
                        <ul class="nav navbar-nav navbar-right" id="navbar_deslogar">
                            <li class="dropdown">
                                <a href="/auth/logout">Deslogar</a>
                            </li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div><!-- /.container-fluid -->
            </nav>
            <?php } ?>
        </header>
