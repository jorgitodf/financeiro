<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('conta_model');
		$this->load->helper('url');
	}

	public function index()
	{
		$dados["title"] = "Home";
		$dados["view"] = "home/v_home";
		
		if ($this->session->userdata('id') != null && $this->session->userdata('user') != null) {
			if ($this->conta_model->verificaConta($this->session->userdata('id')) == false) {
				$dados["conta"] = "Sr(a). {$this->session->userdata('user')} você não possui nenhuma Conta Cadastrada no momento!";
			} else {
				$dados["contas"] = $this->conta_model->verificaConta($this->session->userdata('id'));
			}
		}
		$this->load->view("v_template", $dados);
	}

}
