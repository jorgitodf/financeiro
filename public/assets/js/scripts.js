$(document).ready(function () {

    //LOGIN DE USUÁRIO
    $(function () {
        $("#form_login").submit(function(e) {
            var email = $("#email").val();
            var password = $("#password").val();
            $(".msgError").html("");
            $(".msgError").css("display", "none");
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: {email: email, password: password},
                dataType: 'json',
                success: function (retorno) {
                    if (retorno[0]['status'] == 'error' ) {
                        $('.retorno').html('<div class="alert alert-danger col-md-12 col-sm-12 col-lg-12 form-group text-center msgError" role="alert" id="msg_error_login">' + retorno[0]['message'] + '</div>');
                    } else if (retorno[0]['status'] == 'success'){
                        redirectPageHome(retorno[0]['base_url'])
                    }
                    else {
                        alert(retorno);
                    }
                },
                fail: function(){
                    alert('ERRO: Falha ao carregar o script.');
                }
            });
        });
    });

    //CADASTRO DE USUÁRIO
    $(function () {
        $("#form_cadastro_usuario").submit(function(e) {
            $(".msgError").html("");
            $(".msgError").css("display", "none");
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: 'json',
                success: function (retorno) {
                    if (retorno[0]['status'] == 'error' ) {
                        $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_cad_usuario">' + retorno[0]['message'] + '</div>');
                    } else if (retorno[0]['status'] == 'success'){
                        $('.retorno').html('<span class="msgError" id="">' + retorno[0]['message'] + '</span>');
                    }
                    else {
                        alert(retorno);
                    }
                },
                fail: function(){
                    alert('ERRO: Falha ao carregar o script.');
                }
            });
        });
    });

    //RELATÓRIO ANUAL
    $(function () {
        $("#form_relatorio_anual").submit(function(e) {
            $(".msgError").html("");
            $(".msgError").css("display", "none");

            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: 'json',
                success: function (retorno) {
                    if (retorno[0]['status'] == 'error') {
                        $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_relarotio_anual">' + retorno[0]['message'] + '</div>');
                    } else if (retorno[0]['status'] == 'success') {
                        $("#sec_relatorios_index").css("display", "none");
                        $("#grafico").css("width", "1090px");
                        $("#grafico").css("background-color", "white");
                        $("#grafico").css("margin-top", "10%");
                        $('#grafico').html(loadChart(retorno[0]['dados']));
                        $('#botao').css("background-color", "white");
                        $('#botao').html("<button type='button' class='btn btn-primary' id='btn_sair_grafico' onclick='fecharGrafico()'>Sair</button>");
                    }
                    else {
                        alert(retorno);
                    }
                },
                fail: function(){
                    alert('ERRO: Falha ao carregar o script.');
                }
            });
        });
    });

    //FORMULÁRIO DE DÉBITO
    $('#btn_novo_debito').click(function () {
        $("#btn_salvar_debito").removeAttr('disabled');
        $("#btn_novo_debito").attr('disabled', 'disabled');
        $("#data_debito").removeAttr('disabled');
        $("#movimentacao").removeAttr('disabled');
        $("#nome_categoria").removeAttr('disabled');
        $("#valor").removeAttr('disabled');
        $('#msg_success_debito').remove();
        $("#data_debito").val("");
        $("#movimentacao").val("");
        $("#nome_categoria").val("");
        $("#valor").val("");
    });
    $(function () {
    $("#form_cad_debito").submit(function (e) {
        $(".msgError").html("");
        $(".msgError").css("display", "none");
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: $(this).serialize(),
            dataType: 'json',
            success: function (retorno) {
                if (retorno[0]['status'] == 'error') {
                    $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_debito">' + retorno[0]['message'] + '</div>');
                } else if (retorno[0]['status'] == 'success'){
                    $('.retorno').html('<div class="alert alert-success text-center msgSuccess" role="alert" id="msg_success_debito">' + retorno[0]['message'] + '</div>');
                    $("#btn_salvar_debito").attr('disabled', 'disabled');
                    $("#btn_novo_debito").removeAttr('disabled');
                    $("#data_debito").attr('disabled', 'disabled');
                    $("#movimentacao").attr('disabled', 'disabled');
                    $("#nome_categoria").attr('disabled', 'disabled');
                    $("#valor").attr('disabled', 'disabled');
                    getSaldo();
                }
                else {
                    alert(retorno);
                }
            },
            fail: function(){
                alert('ERRO: Falha ao carregar o script.');
            }
        });
      });
    });

    //FORMULÁRIO DE CRÉDITO
    $('#btn_novo_credito').click(function () {
        $("#btn_salvar_credito").removeAttr('disabled');
        $("#btn_novo_credito").attr('disabled', 'disabled');
        $("#data_credito").removeAttr('disabled');
        $("#movimentacao").removeAttr('disabled');
        $("#nome_categoria").removeAttr('disabled');
        $("#valor").removeAttr('disabled');
        $('#msg_success_credito').remove();
        $("#data_credito").val("");
        $("#movimentacao").val("");
        $("#nome_categoria").val("");
        $("#valor").val("");
    });
    $(function () {
    $("#form_cad_credito").submit(function (e) {
        $(".msgError").html("");
        $(".msgError").css("display", "none");
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: $(this).attr("action"),
            data: $(this).serialize(),
            dataType: 'json',
            success: function (retorno) {
                if (retorno[0]['status'] == 'error') {
                    $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_debito">' + retorno[0]['message'] + '</div>');
                } else if (retorno[0]['status'] == 'success'){
                    $('.retorno').html('<div class="alert alert-success text-center msgSuccess" role="alert" id="msg_success_credito">' + retorno[0]['message'] + '</div>');
                    $("#btn_salvar_credito").attr('disabled', 'disabled');
                    $("#btn_novo_credito").removeAttr('disabled');
                    $("#data_credito").attr('disabled', 'disabled');
                    $("#movimentacao").attr('disabled', 'disabled');
                    $("#nome_categoria").attr('disabled', 'disabled');
                    $("#valor").attr('disabled', 'disabled');
                    getSaldo();
                }
                else {
                    alert(retorno);
                }
            },
            fail: function(){
                alert('ERRO: Falha ao carregar o script.');
            }
        });
      });
    });


    //CADASTRO DE CARTÃO DE CRÉDITO
	$('#btn_novo_cartao_credito').click(function () {
		$("#btn_salvar_cartao_credito").removeAttr('disabled');
		$("#btn_novo_cartao_credito").attr('disabled', 'disabled');
		$("#num_cartao").removeAttr('disabled');
		$("#data_validade").removeAttr('disabled');
		$("#bandeira").removeAttr('disabled');
		$("#banco").removeAttr('disabled');
		$('#msg_success_cartao_credito_cadastro').remove();
		$("#num_cartao").val("");
		$("#data_validade").val("");
		$("#bandeira").val("");
		$("#banco").val("");
	});
	$(function () {
		$("#form_cadastro_cartao_credito").submit(function (e) {
			$(".msgError").html("");
			$(".msgError").css("display", "none");
			e.preventDefault();
			$.ajax({
				type: "POST",
				url: $(this).attr("action"),
				data: $(this).serialize(),
				dataType: 'json',
				success: function (retorno) {
                    if (retorno[0]['status'] == 'error') {
                        $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_debito">' + retorno[0]['message'] + '</div>');
					} else if (retorno[0]['status'] == 'success'){
						$('.retorno').html('<div class="alert alert-success text-center msgSuccess" role="alert" id="msg_success_cartao_credito_cadastro">' + retorno[0]['message'] + '</div>');
						$("#btn_salvar_cartao_credito").attr('disabled', 'disabled');
						$("#btn_novo_cartao_credito").removeAttr('disabled');
						$("#num_cartao").attr('disabled', 'disabled');
						$("#data_validade").attr('disabled', 'disabled');
						$("#bandeira").attr('disabled', 'disabled');
						$("#banco").attr('disabled', 'disabled');
					}
					else {
						alert(retorno);
						// alert com erro caso não seja um retorno json.
					}
				},
				fail: function(){
					alert('ERRO: Falha ao carregar o script.');
					// Erro caso o arquivo não seja encontrado ou falhou ao ser carregado.
				}
			});


		});
    });

    
    //LANÇAR DÉBITO (COMPRA) CARTÃO DE CRÉDITO
    $('#btn_nova_lanc_fatura').click(function () {
        $("#btn_salvar_novo_lanc_fatura").removeAttr('disabled');
        $("#btn_nova_lanc_fatura").attr('disabled', 'disabled');
        $("#cartao").removeAttr('disabled');
        $("#data_compra").removeAttr('disabled');
        $("#descricao").removeAttr('disabled');
        $("#valor_compra").removeAttr('disabled');
        $("#parcela").removeAttr('disabled');
        $('#msg_success_lancar_compra_cartao_credito').remove();
        $("#cartao").val("");
        $("#data_compra").val("");
        $("#descricao").val("");
        $("#valor_compra").val("");
        $("#parcela").val("");
    });
    $(function () {
        $("#form_deb_fatura_cartao_credito").submit(function (e) {
            $(".msgError").html("");
            $(".msgError").css("display", "none");
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: 'json',
                success: function (retorno) {
                    if (retorno[0]['status'] == 'error' ){
                        $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_lancar_compra_cartao_credito">' + retorno[0]['message'] + '</div>');
					} else if (retorno[0]['status'] == 'success'){
						$('.retorno').html('<div class="alert alert-success text-center msgSuccess" role="alert" id="msg_success_lancar_compra_cartao_credito">' + retorno[0]['message'] + '</div>');
                        $("#btn_salvar_novo_lanc_fatura").attr('disabled', 'disabled');
                        $("#btn_nova_lanc_fatura").removeAttr('disabled');
                        $("#cartao").attr('disabled', 'disabled');
                        $("#data_compra").attr('disabled', 'disabled');
                        $("#descricao").attr('disabled', 'disabled');
                        $("#valor_compra").attr('disabled', 'disabled');
                        $("#parcela").attr('disabled', 'disabled');
                    }
                    else {
                        alert(retorno);
                    }
                },
                fail: function(){
                    alert('ERRO: Falha ao carregar o script.');
                }
            });
        });
    });


    //AGENDAMENTO DE PAGAMENTOS
    $('#btn_novo_agendamento').click(function () {
        $("#btn_salvar_agendamento").removeAttr('disabled');
        $("#btn_novo_agendamento").attr('disabled', 'disabled');
        $("#data_pgto").removeAttr('disabled');
        $("#movimentacao").removeAttr('disabled');
        $("#nome_categoria").removeAttr('disabled');
        $("#valor").removeAttr('disabled');
        $('#msg_success_agendar_pagamento').remove();
        $("#data_pgto").val("");
        $("#movimentacao").val("");
        $("#nome_categoria").val("");
        $("#valor").val("");
    });
    $(function () {
        $("#form_cad_agendamento_debito").submit(function (e) {
            $(".msgError").html("");
            $(".msgError").css("display", "none");
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: 'json',
                success: function (retorno) {
                    if (retorno[0]['status'] == 'error' ){
                        $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_agendar_pagamento">' + retorno[0]['message'] + '</div>');
					} else if (retorno[0]['status'] == 'success'){
						$('.retorno').html('<div class="alert alert-success text-center msgSuccess" role="alert" id="msg_success_agendar_pagamento">' + retorno[0]['message'] + '</div>');
                        $("#btn_salvar_agendamento").attr('disabled', 'disabled');
                        $("#btn_novo_agendamento").removeAttr('disabled');
                        $("#data_pgto").attr('disabled', 'disabled');
                        $("#movimentacao").attr('disabled', 'disabled');
                        $("#nome_categoria").attr('disabled', 'disabled');
                        $("#valor").attr('disabled', 'disabled');
                    }
                    else {
                        alert(retorno);
                    }
                },
                fail: function(){
                    alert('ERRO: Falha ao carregar o script.');
                }
            });
        });
    });


    //ALTERAR PAGAMENTO AGENDADO
    $('#btn_editar_pgto_agendado').click(function () {
        $("#btn_salvar_pgto_agendado").removeAttr('disabled');
        $("#btn_editar_pgto_agendado").attr('disabled', 'disabled');
        $("#data_pgto").removeAttr('disabled');
        $("#mov_pgto").removeAttr('disabled');
        $("#categoria_pgto").removeAttr('disabled');
        $("#valor_pgto").removeAttr('disabled');
        $('#msg_success_alterar_pgto_agendado').remove();
    });
    $(function () {
        $("#form_editar_agendamento_debito").submit(function (e) {
            $(".msgError").html("");
            $(".msgError").css("display", "none");
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: 'json',
                success: function (retorno) {
                    if (retorno[0]['status'] == 'error' ){
                        $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_alterar_pgto_agendado">' + retorno[0]['message'] + '</div>');
					} else if (retorno[0]['status'] == 'success'){
						$('.retorno').html('<div class="alert alert-success text-center msgSuccess" role="alert" id="msg_success_alterar_pgto_agendado">' + retorno[0]['message'] + '</div>');
                        $("#btn_salvar_pgto_agendado").attr('disabled', 'disabled');
                        $("#btn_editar_pgto_agendado").removeAttr('disabled');
                        $("#data_pgto").attr('disabled', 'disabled');
                        $("#mov_pgto").attr('disabled', 'disabled');
                        $("#categoria_pgto").attr('disabled', 'disabled');
                        $("#valor_pgto").attr('disabled', 'disabled');
                    }
                    else {
                        alert(retorno);
                    }
                },
                fail: function(){
                    alert('ERRO: Falha ao carregar o script.');
                }
            });
        });
    });


    //CRIAR FATURA DE CARTÃO DE CRÉDITO
    $('#btn_nova_fatura').click(function () {
        $("#btn_salvar_nova_fatura").removeAttr('disabled');
        $("#btn_nova_fatura").attr('disabled', 'disabled');
        $("#cartao").removeAttr('disabled');
        $("#data_vencimento").removeAttr('disabled');
        $('#msg_success_criar_fatura_cartao_credito').remove();
        $("#cartao").val("");
        $("#data_vencimento").val("");
    });
    $(function () {
        $("#form_cadastro_fatura_cartao_credito").submit(function (e) {
            $(".msgError").html("");
            $(".msgError").css("display", "none");
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: 'json',
                success: function (retorno) {
                    if (retorno[0]['status'] == 'error' ) {
                        $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_criar_fatura_cartao_credito">' + retorno[0]['message'] + '</div>');
					} else if (retorno[0]['status'] == 'success'){
						$('.retorno').html('<div class="alert alert-success text-center msgSuccess" role="alert" id="msg_success_criar_fatura_cartao_credito">' + retorno[0]['message'] + '</div>');
                        $("#btn_salvar_nova_fatura").attr('disabled', 'disabled');
                        $("#btn_nova_fatura").removeAttr('disabled');
                        $("#btn_debitar_fatura").removeAttr('disabled');
                        $("#cartao").attr('disabled', 'disabled');
                        $("#data_vencimento").attr('disabled', 'disabled');
                    }
                    else {
                        alert(retorno);
                    }
                },
                fail: function(){
                    alert('ERRO: Falha ao carregar o script.');
                }
            });
        });
    });


    //CRIAR FECHAR DE CARTÃO DE CRÉDITO
    $(function () {
        $("#form_buscar_fatura_fechar").submit(function (e) {
            $(".msgError").html("");
            $(".msgError").css("display", "none");
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: 'json',
                success: function (retorno) {
                    if (retorno[0]['status'] == 'error' ) {
                        $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_fechar_fatura_cartao_credito">' + retorno[0]['message'] + '</div>');
					} else if (retorno[0]['status'] == 'success'){
                        window.setTimeout(redirectPagarFatura(retorno[0]['base_url'], retorno[0]['id_fatura_cartao']), 1);
                    }
                    else {
                        alert(retorno);
                    }
                },
                fail: function(){
                    alert('ERRO: Falha ao carregar o script.');
                }
            });
        });
    });


    //PAGAMENTO DE FATURA DE CARTÃO DE CRÉDITO PARA PAGAMENTO
    $('#btn_limpar_pgto_fatura').click(function () {
        $("#encargos").val("");
        $("#iof").val("");
        $("#anuidade").val("");
        $("#protecao_prem").val("");
        $("#juros_fat").val("");
        $("#restante").val("");
        $("#valor_total").val("");
        $("#valor_pagar").val("");
        $('#msg_error_pgto_fatura_cartao').remove();
    });

    $('#btn_novo_pgto_fatura').click(function () {
        $("#btn_novo_pgto_fatura").attr('disabled', 'disabled');
        $("#btn_calcular_fatura").removeAttr('disabled');
        $("#btn_pagar_fatura").removeAttr('disabled');
        $("#btn_limpar_pgto_fatura").removeAttr('disabled');
    });
    $(function () {
        $("#form_pagar_fatura").submit(function (e) {
            $(".msgError").html("");
            $(".msgError").css("display", "none");
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: $(this).serialize(),
                dataType: 'json',
                success: function (retorno) {
                    if (retorno[0]['status'] === 'error' ){
                        $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_pgto_fatura_cartao">' + retorno[0]['message'] + '</div>');
                    } else if (retorno[0]['status'] === 'success'){
                        $('.retorno').html('<div class="alert alert-success text-center msgSuccess" id="msg_success_pgto_fatura_cartao">' + retorno[0]['message'] + '</div>');
                        $("#btn_novo_pgto_fatura").attr('disabled', 'disabled');
                        $("#btn_calcular_fatura").attr('disabled', 'disabled');
                        $("#btn_pagar_fatura").attr('disabled', 'disabled');
                        $("#btn_limpar_pgto_fatura").attr('disabled', 'disabled');
                        $("#encargos").attr('disabled', 'disabled');
                        $("#iof").attr('disabled', 'disabled');
                        $("#anuidade").attr('disabled', 'disabled');
                        $("#protecao_prem").attr('disabled', 'disabled');
                        $("#juros_fat").attr('disabled', 'disabled');
                        $("#restante").attr('disabled', 'disabled');
                        $("#valor_pagar").attr('disabled', 'disabled');
                        $("#valor_total").attr('disabled', 'disabled');
                    } else {
                        alert(retorno);
                    }
                },
                fail: function(){
                    alert('ERRO: Falha ao carregar o script.');
                }
            });
        });
    });

    $('#btn_calcular_fatura').click(function () {
        var id_cartao_cre = $('#id_cartao_cre').val();
        var str = $('#subtotal').val();
        var subtotal = replace(str);

        var str = $('#encargos').val();
        var encargos = replace(str);
        if (encargos === "") {
            encargos = 0;
        }
        var str = $('#iof').val();
        var iof = replace(str);
        if (iof === "") {
            iof = 0;
        }
        var str = $('#anuidade').val();
        var anuidade = replace(str);
        if (anuidade === "") {
            anuidade = 0;
        }
        var str = $('#protecao_prem').val();
        var protecao_prem = replace(str);
        if (protecao_prem === "") {
            protecao_prem = 0;
        }
        var str = $('#juros_fat').val();
        var juros_fat = replace(str);
        if (juros_fat === "") {
            juros_fat = 0;
        }
        var str = $('#restante').val();
        var restante = replace(str);
        if (restante === "") {
            restante = 0;
        }
        var total = 0;
        if (subtotal > 0) {
            total = (parseFloat(subtotal) + parseFloat(encargos) + parseFloat(iof) + parseFloat(anuidade) + parseFloat(protecao_prem)
                + parseFloat(juros_fat) + parseFloat(restante));
            //var num = total.toString();   
            //var tot = num.replace('.',',');
        }
        $(".msgError").html("");
        $(".msgError").css("display", "none");
        $.ajax({
            type: "POST",
            url: $('#action').val(),
            data: {id_cartao_cre: id_cartao_cre},
            dataType: 'json',
            success: function (retorno) {
                if (retorno[0]['status'] === 'error' ){
                    $('.retorno').html('<span class="msgError" id="">' + retorno[0]['message'] + '</span>');
                } else if (retorno[0]['status'] === 'success'){
                    var val = retorno[0]['message'].toString();
                    var res = val.replace('.',',');
                    $('#restante').val('R$ ' + res);
                } else {
                    alert(retorno);
                }
            },
            fail: function(){
                alert('ERRO: Falha ao carregar o script.');
            }
        });
        $('#valor_total').val(numberToReal(total));
    });
    
    function replace(str) {
        var encargos = str.replace('R$', '');
        encargos = encargos.replace(',', '.'); 
        return encargos;
    }
    
    function arred(val,casas) { 
        var aux = Math.pow(2,casas);
        return Math.floor(val * aux) / aux;
    }


});


function redirectToHome() {
    return document.location.href = "/";
}

function numberToReal(numero) {
    var numero = numero.toFixed(2).split('.');
    numero[0] = "R$ " + numero[0].split(/(?=(?:...)*$)/).join('.');
    return numero.join(',');
}

function getSaldo() {
    var saldo_nav = "Olá";
    $.ajax({
        type: "POST",
        url: '/conta/getSaldo',
        data: {saldo_nav: saldo_nav},
        dataType: 'json',
        success: function (retorno) {
            if (retorno[0]['status'] == 'error' ){
                alert(retorno[0]['message']);
            } else if (retorno[0]['status'] == 'success'){
                $('#saldo_nav').html(retorno[0]['message']);
            }
            else {
                alert(retorno);
            }
        },
        fail: function(){
            alert('ERRO: Falha ao carregar o script.');
        }
    });
}   

function redirectPagarFatura(base_url, id) {
    return window.location.replace(base_url+"/cartaocredito/fatura-pagar/"+id);
}

function redirectRelatorioListarAnual(base_url) {
    return window.location.replace(base_url+"/relatorio/listar-anual");
}

function redirectPageHome(base_url) {
    return window.location.replace(base_url+"/");
}

function loadChart(dados) {
    var canvas = document.getElementById("grafico").getContext("2d");
    var grafico = new Chart(canvas, {
        type: 'line',
        data: {
            labels: [dados['2012'][0].mes, dados['2012'][1].mes, dados['2012'][2].mes, dados['2012'][3].mes, dados['2012'][4].mes,
            dados['2012'][5].mes, dados['2012'][6].mes, dados['2012'][7].mes, dados['2012'][8].mes, dados['2012'][9].mes,
            dados['2012'][10].mes, dados['2012'][11].mes],
            datasets: [
                {
                    label: dados['2012'][0].categoria +" "+ dados['2012'][0].ano,
                    backgroundColor: 'red',
                    borderColor: 'red',
                    data: [dados['2012'][0].total, dados['2012'][1].total, dados['2012'][2].total, dados['2012'][3].total, dados['2012'][4].total,
                        dados['2012'][5].total, dados['2012'][6].total, dados['2012'][7].total, dados['2012'][8].total, dados['2012'][9].total,
                        dados['2012'][10].total, dados['2012'][11].total],
                    fill: false
                },
                {
                    label: dados['2013'][0].categoria +" "+ dados['2013'][0].ano,
                    backgroundColor: 'green',
                    borderColor: 'green',
                    data: [dados['2013'][0].total, dados['2013'][1].total, dados['2013'][2].total, dados['2013'][3].total, dados['2013'][4].total,
                        dados['2013'][5].total, dados['2013'][6].total, dados['2013'][7].total, dados['2013'][8].total, dados['2013'][9].total,
                        dados['2013'][10].total, dados['2013'][11].total],
                    fill: false
                },
                {
                    label: dados['2014'][0].categoria +" "+ dados['2014'][0].ano,
                    backgroundColor: 'blue',
                    borderColor: 'blue',
                    data: [dados['2014'][0].total, dados['2014'][1].total, dados['2014'][2].total, dados['2014'][3].total, dados['2014'][4].total,
                        dados['2014'][5].total, dados['2014'][6].total, dados['2014'][7].total, dados['2014'][8].total, dados['2014'][9].total,
                        dados['2014'][10].total, dados['2014'][11].total],
                    fill: false
                },
                {
                    label: dados['2015'][0].categoria +" "+ dados['2015'][0].ano,
                    backgroundColor: 'brown',
                    borderColor: 'brown',
                    data: [dados['2015'][0].total, dados['2015'][1].total, dados['2015'][2].total, dados['2015'][3].total, dados['2015'][4].total,
                        dados['2015'][5].total, dados['2015'][6].total, dados['2015'][7].total, dados['2015'][8].total, dados['2015'][9].total,
                        dados['2015'][10].total, dados['2015'][11].total],
                    fill: false
                },
                {
                    label: dados['2016'][0].categoria +" "+ dados['2016'][0].ano,
                    backgroundColor: 'yellow',
                    borderColor: 'yellow',
                    data: [dados['2016'][0].total, dados['2016'][1].total, dados['2016'][2].total, dados['2016'][3].total, dados['2016'][4].total,
                        dados['2016'][5].total, dados['2016'][6].total, dados['2016'][7].total, dados['2016'][8].total, dados['2016'][9].total,
                        dados['2016'][10].total, dados['2016'][11].total],
                    fill: false
                },
                {
                    label: dados['2017'][0].categoria +" "+ dados['2017'][0].ano,
                    backgroundColor: 'grey',
                    borderColor: 'grey',
                    data: [dados['2017'][0].total, dados['2017'][1].total, dados['2017'][2].total, dados['2017'][3].total, dados['2017'][4].total,
                        dados['2017'][5].total, dados['2017'][6].total, dados['2017'][7].total, dados['2017'][8].total, dados['2017'][9].total,
                        dados['2017'][10].total, dados['2017'][11].total],
                    fill: false
                },
                {
                    label: dados['2018'][0].categoria +" "+ dados['2018'][0].ano,
                    backgroundColor: 'red',
                    borderColor: 'red',
                    data: [dados['2018'][0].total, dados['2018'][1].total, dados['2018'][2].total, dados['2018'][3].total, dados['2018'][4].total,
                        dados['2018'][5].total, dados['2018'][6].total, dados['2018'][7].total, dados['2018'][8].total, dados['2018'][9].total,
                        dados['2018'][10].total, dados['2018'][11].total],
                    fill: false
                }
            ]
        },
    });
}

function fecharGrafico() {
    $("#div_grafico").remove();
    $("#sec_relatorios_index").css("display", "block");
    window.location.reload();
}
