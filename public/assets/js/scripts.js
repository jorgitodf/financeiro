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
                        $('.retorno').html('<span class="msgError" id="">' + retorno[0]['message'] + '</span>');
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
                        $('.retorno').html('<span class="msgError" id="">' + retorno[0]['message'] + '</span>');
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

});


function redirectToHome() {
    return document.location.href = "/";
}
