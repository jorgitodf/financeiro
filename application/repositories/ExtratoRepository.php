<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/DefaultRepository.php';
require_once __DIR__ . '/CategoriaRepository.php';


class ExtratoRepository extends DefaultRepository
{
    protected $extrato_model;
    protected $categoria;

    public function __construct()
    {
        $this->categoria = new Categoria_model();
        $this->extrato_model = new Extrato_model();
        $this->load->helper("funcoes");
    }

    public function getAllExtrato()
    {
        $resultado = $this->select("SELECT * FROM {$this->extrato->getTable()}");
        return $resultado->result_array();
    }

    public function debitar(Extrato_model $extrato)
    {
        if (!empty($extrato)) {
            $data_movimentacao = $extrato->getDataMovimentacao();
            $mes = $extrato->getMes();
            $tipo_operacao = $extrato->getTipoOperacao();
            $movimentação  = $extrato->getMovimentacao();
            $quantidade = $extrato->getQuantidade();
            $valor = formatarMoeda($extrato->getValor());
            $id_categoria = $extrato->getCategoria()->getIdCategoria();
            $id_conta = $extrato->getConta()->getIdConta();
            $saldo = $this->getSaldoAtual($extrato->getConta()->getIdConta());
            $valor <= $saldo[0]['saldo'];
            $novoSaldo = $saldo[0]['saldo'] - $valor;
            $checkCategoria = $this->categoria->checkCategoria();
                foreach ($checkCategoria as $linha) {
                    if (($linha['id_categoria'] == $extrato->getCategoria()->getIdCategoria()) && ($linha['despesa_fixa'] == 'S')) {
                        $despFixa = 'S';
                    }  elseif (($linha['id_categoria'] == $extrato->getCategoria()->getIdCategoria()) && ($linha['despesa_fixa'] == 'N')) {
                        $despFixa = 'N';
                    }
                }

            $values = ["data_movimentacao"=>$data_movimentacao, "mes"=>$mes, "tipo_operacao"=>$tipo_operacao, "movimentacao"=>$movimentação, "quantidade"=>$quantidade, "valor"=>$valor, "saldo"=>$novoSaldo, 
            "fk_id_categoria"=>$id_categoria, "fk_id_conta"=>$id_conta, "despesa_fixa"=>$despFixa];

            $resultado = $this->insert($extrato->getTable(), $values);

            if ($resultado['status'] == 'success') {
                return array('status' => 'success', 'message' => 'Débito Realizado com Sucesso!');
            } else {
                return array('status' => 'error', 'message' => $resultado['message']);
            }
            
        } else {
            return array('status'=>'error', 'message' => 'ERRO: Possui dados vazios.');
        }
    }

    public function creditar(Extrato_model $extrato)
    {
        if (!empty($extrato)) {
            $data_movimentacao = $extrato->getDataMovimentacao();
            $mes = $extrato->getMes();
            $tipo_operacao = $extrato->getTipoOperacao();
            $movimentação  = $extrato->getMovimentacao();
            $quantidade = $extrato->getQuantidade();
            $valor = formatarMoeda($extrato->getValor());
            $id_categoria = $extrato->getCategoria()->getIdCategoria();
            $id_conta = $extrato->getConta()->getIdConta();
            $saldo = $this->getSaldoAtual($extrato->getConta()->getIdConta());
            $valor <= $saldo[0]['saldo'];
            $novoSaldo = $saldo[0]['saldo'] + $valor;
            $checkCategoria = $this->categoria->checkCategoria();
                foreach ($checkCategoria as $linha) {
                    if (($linha['id_categoria'] == $extrato->getCategoria()->getIdCategoria()) && ($linha['despesa_fixa'] == 'S')) {
                        $despFixa = 'S';
                    }  elseif (($linha['id_categoria'] == $extrato->getCategoria()->getIdCategoria()) && ($linha['despesa_fixa'] == 'N')) {
                        $despFixa = 'N';
                    }
                }

            $values = ["data_movimentacao"=>$data_movimentacao, "mes"=>$mes, "tipo_operacao"=>$tipo_operacao, "movimentacao"=>$movimentação, "quantidade"=>$quantidade, "valor"=>$valor, "saldo"=>$novoSaldo, 
            "fk_id_categoria"=>$id_categoria, "fk_id_conta"=>$id_conta, "despesa_fixa"=>$despFixa];

            $resultado = $this->insert($extrato->getTable(), $values);

            if ($resultado['status'] == 'success') {
                return array('status' => 'success', 'message' => 'Crédito Realizado com Sucesso!');
            } else {
                return array('status' => 'error', 'message' => $resultado['message']);
            }
            
        } else {
            return array('status'=>'error', 'message' => 'ERRO: Possui dados vazios.');
        }
    }

    public function getSaldoAtual(int $idConta): array
    {
        if (!empty($idConta)) {
            $dataAtual = date("Y-m-d");
            $dataMenor = date("Y-m-d", strtotime("-20 days", strtotime($dataAtual)));
            $sql = "SELECT ext.saldo as saldo, concat(con.numero_conta,'-',con.digito_verificador_conta) as
                  conta, ban.nome_banco as banco FROM tb_extrato as ext JOIN 
                  tb_conta as con ON (ext.fk_id_conta = con.id_conta) 
                  JOIN tb_banco as ban ON (con.fk_cod_banco = ban.cod_banco)
                  WHERE ext.data_movimentacao BETWEEN ? AND ? AND ext.fk_id_conta = ? ORDER BY ext.id_extrato DESC LIMIT 1";
            return $this->conection($sql, [$dataMenor, $dataAtual, $idConta])->result_array();
        }
    }

    public function getExtratoAtual($idConta) 
    {
        if (!empty($idConta)) {
            $ano = date("Y");
            $mes = date("m");
            $values = [$idConta, "{$ano}-{$mes}-01", "{$ano}-{$mes}-31"];
            $resultado = $this->selectWhereId("SELECT ext.data_movimentacao AS data_movimentacao, ext.movimentacao 
                AS mov, cat.nome_categoria AS cat, ext.tipo_operacao AS op, ext.valor AS val, ext.saldo AS sal, 
                ext.despesa_fixa AS dp FROM {$this->extrato_model->getTable()} AS ext LEFT JOIN {$this->categoria->getTable()} AS cat 
                ON (ext.fk_id_categoria = cat.id_categoria) WHERE ext.fk_id_conta = ? AND ext.data_movimentacao BETWEEN ? AND ?", $values);
            return $resultado->result_array();
        }
    }

    public function gerarRelatorioAnual(int $ano, int $idCategoria)
    {
        if (!empty($ano)) {
            $sql = "SELECT cat.nome_categoria AS categoria, tb1.total AS 'Janeiro', tb2.total AS 'Fevereiro', 
            tb3.total AS 'Março', tb4.total AS 'Abril', tb5.total AS 'Maio', tb6.total AS 'Junho', tb7.total AS 'Julho', 
            tb8.total AS 'Agosto', tb9.total AS 'Setembro', tb10.total AS 'Outubro', tb11.total AS 'Novembro', 
            tb12.total AS 'Dezembro', fncCalculaValorTotal({$idCategoria}}, {$ano}) AS Total
            FROM {$this->categoria->getTable()} cat
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e
                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-01-01' AND '{$ano}-01-31'
                AND e.tipo_operacao = 'Débito') AS tb1 ON (tb1.id_categoria = cat.id_categoria)
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e
                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-02-01' AND '{$ano}-02-29'
                AND e.tipo_operacao = 'Débito') AS tb2 ON (tb2.id_categoria = cat.id_categoria)
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e
                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-03-01' AND '{$ano}-03-31'
                AND e.tipo_operacao = 'Débito') AS tb3 ON (tb3.id_categoria = cat.id_categoria)
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e

                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-04-01' AND '{$ano}-04-30'
                AND e.tipo_operacao = 'Débito') AS tb4 ON (tb4.id_categoria = cat.id_categoria)
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e
                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-05-01' AND '{$ano}-05-31'
                AND e.tipo_operacao = 'Débito') AS tb5 ON (tb5.id_categoria = cat.id_categoria)
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e
                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-06-01' AND '{$ano}-06-30'
                AND e.tipo_operacao = 'Débito') AS tb6 ON (tb6.id_categoria = cat.id_categoria)
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e

                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-07-01' AND '{$ano}-07-31'
                AND e.tipo_operacao = 'Débito') AS tb7 ON (tb7.id_categoria = cat.id_categoria)
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e
                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-08-01' AND '{$ano}-08-31'
                AND e.tipo_operacao = 'Débito') AS tb8 ON (tb8.id_categoria = cat.id_categoria)
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e
                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-09-01' AND '{$ano}-09-30'
                AND e.tipo_operacao = 'Débito') AS tb9 ON (tb9.id_categoria = cat.id_categoria)
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e
                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-10-01' AND '{$ano}-10-31'
                AND e.tipo_operacao = 'Débito') AS tb10 ON (tb10.id_categoria = cat.id_categoria)
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e
                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-11-01' AND '{$ano}-11-30'
                AND e.tipo_operacao = 'Débito') AS tb11 ON (tb11.id_categoria = cat.id_categoria)
            JOIN (
                SELECT IFNULL(SUM(e.valor), 0.00) AS total, IFNULL(e.fk_id_categoria, {$idCategoria}) AS id_categoria
                FROM {$this->extrato_model->getTable()} e
                WHERE e.fk_id_categoria = {$idCategoria}
                AND data_movimentacao BETWEEN '{$ano}-12-01' AND '{$ano}-12-31'
                AND e.tipo_operacao = 'Débito') AS tb12 ON (tb12.id_categoria = cat.id_categoria)";
                return $this->db->query($sql)->result_array();
        } else {
            return array('status'=>'error', 'message' => 'ERRO: Possui dados vazios.');
        }
    }
}    