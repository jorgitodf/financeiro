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
        date_default_timezone_set('America/Sao_Paulo');
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
}    