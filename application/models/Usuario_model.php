<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/../../system/core/Model.php';

class Usuario_model extends CI_Model
{
    protected $table = 'tb_usuario';
    private $nome;
    private $email;
    private $senha;
    private $repeat_senha;

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper("funcoes");
    }

    public function getTable() {
        return $this->table;
    }

    public function getNome()
    {
        return $this->nome;
    }
    public function setNome($nome)
    {
        if (empty($nome)) {
            return array('status'=>'error', 'message'=>'Preencha o seu Nome!');
        } else {
            $this->nome = $nome;
        }
    }

    public function getEmaiil()
    {
        return $this->email;
    }
    public function setEmaiil($email)
    {
        if (empty($email)) {
            return array('status'=>'error', 'message'=>'Preencha o seu E-mail!');
        } else {
            $this->email = $email;
        }
    }

    public function getSenha()
    {
        return $this->senha;
    }
    public function setSenha($senha)
    {
        if (empty($senha)) {
            return array('status'=>'error', 'message'=>'Preencha a sua Senha!');
        } else {
            $this->senha = $senha;
        }
    }

    public function getRepeatSenha()
    {
        return $this->repeat_senha;
    }
    public function setRepeatSenha($repeat_senha)
    {
        if (empty($repeat_senha)) {
            return array('status'=>'error', 'message'=>'Redigite a sua Senha!');
        } elseif ($repeat_senha != $this->senha) {
            return array('status'=>'error', 'message'=>'As senhas não são iguais!');
        } else {
            $this->repeat_senha = $repeat_senha;
        }
    }

    public function getAllUsuarios()
    {
        $usuarios = $this->db->get($this->table);
        return $usuarios->result_array();
    }

    public function deleteUsuario($id)
    {
        $usuarios = $this->db->delete($this->table, ['id_usuario' => $id]);
        return $usuarios->result_array();
    }

    public function updateUsuario(array $dados, $id)
    {
        $usuarios = $this->db->update($this->table, $dados, ['id_usuario' => $id]);
        return $usuarios->result_array();
    }

    public function getEmailSenha($email, $password)
    {
        try {
            $sql = "SELECT id_usuario, nome, email, senha FROM {$this->table} WHERE email = ?";
            $dados = $this->db->query($sql, [$email]);
			if ($dados->result_array() == null) {
                return array('status'=>'error', 'message' => 'Usuário Não Cadastrado!');
            } elseif ($dados->result_array()[0]['email'] && (consultaSenhaCrypty($password, $dados->result_array()[0]['senha']) != true)) {
                return array('status'=>'error', 'message' => 'A senha digitada não confere com a senha Cadastrada!');
            }
            return array('status'=>'success', 'id_usuario' => $dados->result_array()[0]['id_usuario'], 'nome_usuario' => $dados->result_array()[0]['nome']);
        } catch (Exception $e) {
            return array('status'=>'error', 'message' => $e->getMessage());
        }
    }
}
