<?php
defined('BASEPATH') OR exit('No direct script access allowed');

function monta_tabela_pagto_agendado($mes, $ano, $contas_agendadas = null) {
    if (!empty($contas_agendadas) || $contas_agendadas != null) {
        $table = "<table class='table table-responsive table-condensed bordasimples' id='table_desp_agendada' cellspacing=1 cellpadding=1>";
            $table .= "<thead>";
                $table .= "<tr>";
                $table .= "<td colspan='4' id='cab_table'>Contas Agendadas para {$mes} / {$ano}</td>";
                $table .= "</tr>";
                $table .= "<tr id='tr_cab_table'>";
                    $table .= "<td class='largura_td tam_fonte'>Movimentação</td>";
                    $table .= "<td class='tam_fonte' id='largura_td_valor'>Valor</td>";
                    $table .= "<td class='tam_fonte' id='largura_td_data'>Data Pagamento</td>";
                    $table .= "<td class='tam_fonte' id=''>Pago</td>";
                $table .= "</tr>";
            $table .= "</thead>";
            $table .= "<tbody>";
                $total = 0; 
                foreach ($contas_agendadas as $linha) {
                $table .= "<tr>";
                if ($linha['pago'] == 'Não') {
                    $table .= "<td class='td_color_pgto'>".mb_convert_case($linha['mov'], MB_CASE_TITLE)."</td>";
                    $table .= "<td align='left' class='td_color_pgto' id=''>R$ ".number_format($linha['valor'], 2, ',', '.')."</td>";
                    $table .= "<td class='td_color_pgto' id=''>".date("d/m/Y", strtotime($linha['data']))."</td>";
                    $table .= "<td class='td_color_pgto' id=''>".mb_convert_case($linha['pago'], MB_CASE_TITLE)."</td>";
                } else {
                    $table .= "<td class='td_color_pgto_sim'>".mb_convert_case($linha['mov'], MB_CASE_TITLE)."</td>";
                    $table .= "<td align='left' class='td_color_pgto_sim' id=''>R$ ".number_format($linha['valor'], 2, ',', '.')."</td>";
                    $table .= "<td class='td_color_pgto_sim' id=''>".date("d/m/Y", strtotime($linha['data']))."</td>";
                    $table .= "<td class='td_color_pgto_sim' id=''>".mb_convert_case($linha['pago'], MB_CASE_TITLE)."</td>";
                }
                $table .= "</tr>";
                    $total += $linha['valor'];
                }
            $table .= "</tbody>";
            $table .= "<tfoot>";
                $table .= "<tr class=''>";
                $table .= "<td colspan='2' align='center' class='cor_preta tam_fonte'>Total de Contas a Pagar em {$mes}/{$ano}</td>";
                $table .= "<td colspan='2' align='right' class='cor_preta tam_fonte' >R$ ".number_format($total, 2, ',', '.')."</td>";
                $table .= "</tr>";
            $table .= "<tfoot>";
        $table .= "</table>";
    } else {
        $table = "<table class='table table-condensed bordasimples' id='table_desp_agendada' cellspacing=1 cellpadding=1>";
            $table .= "<thead>";
                $table .= "<tr>";
                $table .= "<td colspan='4' id='cab_table'>Contas Agendadas para {$mes} / {$ano}></td>";
                $table .= "</tr>";
                $table .= "<tr id='tr_cab_table'>";
                $table .= "<td>Movimentação</td>";
                $table .= "<td>Valor</td>";
                $table .= "<td>Data Pagamento</td>";
                $table .= "<td>Pago</td>";
                $table .= "</tr>";
            $table .= "</thead>";
            $table .= "<body>";
            $table .= "<tr>";
            $table .= "<td colspan='4' id='td_sem_contas_agendadas'>Sem Pagamento Agendado Até o Momento</td>";
            $table .= "</tr>";
            $table .= "</body>";
            $table .= "<tfoot>";
                $table .= "<tr>";
                $table .= "<td colspan='2'></td>";
                $table .= "<td colspan='2'></td>";
                $table .= "</tr>";
            $table .= "<tfoot>";
        $table .= "</table>";
    }
    return $table;
}