<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function cryptySenha($senha) {
    $custo = '09';
    $salt = randString();
    $hash = crypt($senha, '$2a$' . $custo . '$' . $salt . '$');
    return $hash;
}

function randString() {
    $basic = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnPpQqRrSsTtUuVvYyXxWwZz0123456789';
    $return = "";
    for($count = 0; 22 > $count; $count++){
        $return .= $basic[rand(0, strlen($basic) - 1)];
    }
    $return1 = substr($return, 0, 22);
    return $return1;
}

function stripHTMLtags($str) {
    $t = preg_replace('/<[^<|>]+?>/', '', htmlspecialchars_decode($str));
    $t = htmlentities($t, ENT_QUOTES, "UTF-8");
    return $t;
}

function consultaSenhaCrypty($senha, $hash) {
    if (crypt($senha, $hash) === $hash) {
        return true;
    } else {
        return false;
    }
}

function formatarMoeda($valor) {
    $valor1 = trim(str_replace('R$ ', '', $valor));
    $number = str_replace(',','.',preg_replace('#[^\d\,]#is','',$valor1)); 
    return number_format((float) $number, 2, "." ,"");
}

function removePontos($numero) {
	$valor = trim(str_replace('.', '', $numero));
	return $valor;
}

function formataData($data) {
    if (!empty($data)) {
        $d = explode("/", $data);
        $data_format = (trim($d[2]."-".$d[1]."-".$d[0]));
        return $data_format;
    }
}

function verificaMes() {
    date_default_timezone_set('America/Sao_Paulo');
    $mesAtual = date("m");
    switch ($mesAtual) {
        case '01':
            $mesAtual = 'Janeiro';
            break;
        case '02':
            $mesAtual = 'Fevereiro';
            break;
        case '03':
            $mesAtual = 'Março';
            break;
        case '04':
            $mesAtual = 'Abril';
            break;
        case '05':
            $mesAtual = 'Maio';
            break;
        case '06':
            $mesAtual = 'Junho';
            break;
        case '07':
            $mesAtual = 'Julho';
            break;
        case '08':
            $mesAtual = 'Agosto';
            break;
        case '09':
            $mesAtual = 'Setembro';
            break;
        case '10':
            $mesAtual = 'Outubro';
            break;
        case '11':
            $mesAtual = 'Novembro';
            break;
        case '12':
            $mesAtual = 'Dezembro';
            break;
    }
    return $mesAtual;
}

function verificaMesNumerico() {
    date_default_timezone_set('America/Sao_Paulo');
    $mesAtual = date("m");
    switch ($mesAtual) {
        case '01':
            $mesAtual = '01';
            break;
        case '02':
            $mesAtual = '02';
            break;
        case '03':
            $mesAtual = '03';
            break;
        case '04':
            $mesAtual = '04';
            break;
        case '05':
            $mesAtual = '05';
            break;
        case '06':
            $mesAtual = '06';
            break;
        case '07':
            $mesAtual = '07';
            break;
        case '08':
            $mesAtual = '08';
            break;
        case '09':
            $mesAtual = '09';
            break;
        case '10':
            $mesAtual = '10';
            break;
        case '11':
            $mesAtual = '11';
            break;
        case '12':
            $mesAtual = '12';
            break;
    }
    return $mesAtual;
}

function pageNotFound() {
    $dados["title"] = "Page Not Found: 404";
    $dados["message"] = "Erro 404: A página requisita não existe...";
    $dados["view"] = "errors/error_404";
    return $dados;
}

function transformaAnoMes($data) {
	$datahora = strtotime($data);
	$anoMes = date("Y-m", $datahora);
    return $anoMes;
}

function dataPagamento($dataCompra, int $id_cartao) {

    $data_pagamento = "";
    $diaFF = "";
    $difMeses = "";

    $data = new DateTime();
    $anoMesAtual = $data->format('Y-m');
    $dataAtual = $data->format('Y-m-d');

    if ($id_cartao == 1) {
        $diaFF = '29';
    } else if ($id_cartao == 2) {
        $diaFF = '25';
    } else {
        $diaFF = '02';
    }

    $dia_compra = date('d', strtotime($dataCompra));
    $mes_compra = date('m', strtotime($dataCompra));
    $ano_compra = date('Y', strtotime($dataCompra));

    $mes_fatura = date('m', strtotime($dataAtual));

    $difMeses = ($mes_fatura - $mes_compra);

    if (($id_cartao == 1) && ($dia_compra < $diaFF) && ($mes_compra < $mes_fatura) && ($difMeses > 1)) {
        $data_pagamento = date('Y-m-d', strtotime("+{$difMeses} month", strtotime("{$ano_compra}-{$mes_compra}-08")));
    } else if (($id_cartao == 1) && ($dia_compra >= $diaFF) && ($mes_compra < $mes_fatura) && ($difMeses > 1)) {
        $data_pagamento = date('Y-m-d', strtotime("+{$difMeses} month", strtotime("{$ano_compra}-{$mes_compra}-08")));
    } else if (($id_cartao == 1) && ($dia_compra >= $diaFF) && ($mes_compra < $mes_fatura) && ($difMeses == 1)) {
        $data_pagamento = date('Y-m-d', strtotime("+2 month", strtotime("{$ano_compra}-{$mes_compra}-08")));
    } else if (($id_cartao == 1) && ($dia_compra < $diaFF) && ($mes_compra < $mes_fatura) && ($difMeses == 1)) {
        $data_pagamento = date('Y-m-d', strtotime("+1 month", strtotime("{$ano_compra}-{$mes_compra}-08")));
    } else if (($id_cartao == 2) && ($dia_compra < $diaFF) && ($mes_compra < $mes_fatura) && ($difMeses > 1)) {
        $data_pagamento = date('Y-m-d', strtotime("+{$difMeses} month", strtotime("{$ano_compra}-{$mes_compra}-08")));
    } else if (($id_cartao == 2) && ($dia_compra >= $diaFF) && ($mes_compra < $mes_fatura) && ($difMeses > 1)) {
        $data_pagamento = date('Y-m-d', strtotime("+{$difMeses} month", strtotime("{$ano_compra}-{$mes_compra}-08")));
    } else if (($id_cartao == 2) && ($dia_compra >= $diaFF) && ($mes_compra < $mes_fatura) && ($difMeses == 1)) {
        $data_pagamento = date('Y-m-d', strtotime("+2 month", strtotime("{$ano_compra}-{$mes_compra}-08")));
    } else if (($id_cartao == 2) && ($dia_compra < $diaFF) && ($mes_compra < $mes_fatura) && ($difMeses == 1)) {
        $data_pagamento = date('Y-m-d', strtotime("+1 month", strtotime("{$ano_compra}-{$mes_compra}-08")));
    } else if (($id_cartao == 3) && ($dia_compra <= $diaFF) && ($mes_compra == $mes_fatura)) {
        $data_pagamento = date('Y-m-09');
    } else if (($id_cartao == 3) && (($dia_compra <= $diaFF) || ($dia_compra > $diaFF)) && ($mes_compra < $mes_fatura) && ($difMeses == 1)) {
        $data_pagamento = date('Y-m-d', strtotime("+1 month", strtotime("{$ano_compra}-{$mes_compra}-09")));
    } else if (($id_cartao == 3) && (($dia_compra <= $diaFF) || ($dia_compra > $diaFF)) && ($mes_compra < $mes_fatura) && ($difMeses > 1)) {
        $data_pagamento = date('Y-m-d', strtotime("+{$difMeses} month", strtotime("{$ano_compra}-{$mes_compra}-09")));
    } else if (($id_cartao == 3) && ($dia_compra > $diaFF) && ($mes_compra = $mes_fatura)) {
        $data_pagamento = date('Y-m-d', strtotime("+1 month", strtotime("{$ano_compra}-{$mes_compra}-09")));
    } 

    return $data_pagamento;
}
