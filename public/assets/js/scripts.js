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
                        window.setTimeout(document.location.href = retorno[0]['message'], 1);
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

    //CADASTRO DE CONTA
    $(function () {
        $("#form_cadastro_conta").submit(function(e) {
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
                        $('.retorno').html('<div class="alert alert-danger text-center msgError" role="alert" id="msg_error_cad_conta">' + retorno[0]['message'] + '</div>');
                    } else if (retorno[0]['status'] == 'success'){
                        $('.retorno').html('<div class="alert alert-success text-center msgSuccess" role="alert" id="msg_success_cad_conta">' + retorno[0]['message'] + '</div>');
                        window.setTimeout(redirectToHome, 4000);
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
            var id_cartao_fat = $('#id_cartao_fat').val();
            var encargos = $('#encargos').val();
            var iof = $('#iof').val();
            var anuidade = $('#anuidade').val();
            var protecao_prem = $('#protecao_prem').val();
            var juros_fat = $('#juros_fat').val();
            var restante = $('#restante').val();
            var valor_pagar = $('#valor_pagar').val();
            var valor_total = $('#valor_total').val();
            $(".msgError").html("");
            $(".msgError").css("display", "none");
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: $(this).attr("action"),
                data: {id_cartao_fat: id_cartao_fat, encargos: encargos, iof: iof, anuidade: anuidade, protecao_prem: protecao_prem,
                        juros_fat: juros_fat, restante: restante, valor_pagar: valor_pagar, valor_total: valor_total},
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