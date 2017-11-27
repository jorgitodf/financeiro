<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/../../system/core/Model.php';

class ItensFaturaDespesas_model extends CI_Model
{
	protected $table = 'tb_itens_fatura_despesas';
	private $id;
	private $despesa_cartao;
	private $fatura_cartao;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('DespesaCartao_model');
		$this->despesa_cartao = new DespesaCartao_model();
        $this->fatura_cartao = new FaturaCartao_model();
		$this->load->helper("funcoes");
	}

	public function getTable() {
		return $this->table;
	}

	public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
	}
	
	public function getDespesaCartao()
    {
        return $this->despesa_cartao;
    }

    public function getFaturaCartao()
    {
        return $this->fatura_cartao;
	}
	
}
