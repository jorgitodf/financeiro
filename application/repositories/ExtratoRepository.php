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

    public function gerarRelatorioAnual($idCategoria, $ano): array
    {
        if (!empty($ano)) {
            $sql = "SELECT LEFT(data_movimentacao, 4) AS ano FROM tb_extrato GROUP BY LEFT(data_movimentacao, 4)";
            $dados = $this->db->query($sql)->result_array();
            foreach ($dados as $item) {
                $sql1[$item['ano']] = "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Janeiro') AS mes, ".
                "IFNULL(LEFT(e.data_movimentacao, 4), {$item['ano']}) AS ano, IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-01-01' AND '{$item['ano']}-01-31' AND c.id_categoria = $idCategoria ".
                "UNION ".
                "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Fevereiro') AS mes, IFNULL(LEFT(e.data_movimentacao, 4), ".
                "{$item['ano']}) AS ano, IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-02-01' AND '{$item['ano']}-02-28' AND c.id_categoria = $idCategoria ".
                "UNION ".
                "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Março') AS mes, IFNULL(LEFT(e.data_movimentacao, 4), ".
                "{$item['ano']}) AS ano, IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-03-01' AND '{$item['ano']}-03-31' AND c.id_categoria = $idCategoria ".
                "UNION ".
                "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Abril') AS mes, IFNULL(LEFT(e.data_movimentacao, 4), {$item['ano']}) AS ano, ".
                "IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-04-01' AND '{$item['ano']}-04-30' AND c.id_categoria = $idCategoria ".
                "UNION ".
                "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Maio') AS mes, IFNULL(LEFT(e.data_movimentacao, 4), {$item['ano']}) AS ano, ".
                "IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-05-01' AND '{$item['ano']}-05-31' AND c.id_categoria = $idCategoria ".
                "UNION ".
                "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Junho') AS mes, IFNULL(LEFT(e.data_movimentacao, 4), {$item['ano']}) AS ano, ".
                "IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-06-01' AND '{$item['ano']}-06-30' AND c.id_categoria = $idCategoria ".
                "UNION ".
                "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Julho') AS mes, IFNULL(LEFT(e.data_movimentacao, 4), {$item['ano']}) AS ano, ".
                "IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-07-01' AND '{$item['ano']}-07-31' AND c.id_categoria = $idCategoria ".
                "UNION ".
                "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Agosto') AS mes, IFNULL(LEFT(e.data_movimentacao, 4), {$item['ano']}) AS ano, ".
                "IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-08-01' AND '{$item['ano']}-08-31' AND c.id_categoria = $idCategoria ".
                "UNION ".
                "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Setembro') AS mes, IFNULL(LEFT(e.data_movimentacao, 4), {$item['ano']}) AS ano, ".
                "IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-09-01' AND '{$item['ano']}-09-30' AND c.id_categoria = $idCategoria ".
                "UNION ".
                "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Outubro') AS mes, IFNULL(LEFT(e.data_movimentacao, 4), {$item['ano']}) AS ano, ".
                "IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-10-01' AND '{$item['ano']}-10-31' AND c.id_categoria = $idCategoria ".
                "UNION ".
                "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Novembro') AS mes, IFNULL(LEFT(e.data_movimentacao, 4), {$item['ano']}) AS ano, ".
                "IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-11-01' AND '{$item['ano']}-11-30' AND c.id_categoria = $idCategoria ".
                "UNION ".
                "SELECT c.nome_categoria AS categoria, IFNULL(e.mes, 'Dezembro') AS mes, IFNULL(LEFT(e.data_movimentacao, 4), {$item['ano']}) AS ano, ".
                "IFNULL(SUM(valor), 0.00) AS total ".
                "FROM tb_extrato e ".
                "JOIN tb_categoria c ON (c.id_categoria = e.fk_id_categoria) ".
                "WHERE e.data_movimentacao BETWEEN '{$item['ano']}-12-01' AND '{$item['ano']}-12-31' AND c.id_categoria = $idCategoria";
            }
            foreach ($sql1 as $key => $value) {
                $data[$key] = $this->db->query($value)->result_array();
            }
            return array('status'=>'success', 'dados' => $data);
        } else {
            return array('status'=>'error', 'message' => 'ERRO: Possui dados vazios.');
        }
    }
}    