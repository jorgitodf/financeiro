<?php
defined('BASEPATH') OR exit('No direct script access allowed');


function cryptySenha($senha) {
    $custo = '09';
    $salt = randString();
    $hash = crypt($senha, '$2a$' . $custo . '$' . $salt . '$');
    return $hash;
}

function randString(){
    $basic = 'AaBbCcDdEeFfGgHhIiJjKkLlMmNnPpQqRrSsTtUuVvYyXxWwZz0123456789';
    $return = "";
    for($count = 0; 22 > $count; $count++){
        $return .= $basic[rand(0, strlen($basic) - 1)];
    }
    $return1 = substr($return, 0, 22);
    return $return1;
}

function consultaSenhaCrypty($senha, $hash) {
    if (crypt($senha, $hash) === $hash) {
        return true;
    } else {
        return false;
    }
}

function formatarMoeda($valor) {
    $valor1 = str_replace('.', '', $valor);
    $valor2 = str_replace(',', '.', $valor1);
    $valor3 = trim(str_replace('R$ ', '', $valor2));
    return $valor3;
}

function removePontos($numero) {
	$valor = trim(str_replace('.', '', $numero));
	return $valor;
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
