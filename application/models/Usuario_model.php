<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Usuario_model extends CI_Model
{
    protected $table = 'tb_usuario';

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->helper("funcoes");
    }

    public function getTable() {
        return $this->table;
    }

    public function getEmail($email)
    {
        $usuario = $this->db->get_where($this->table, ['email' => $email]);
        return $usuario->custom_row_object(0, 'Usuario_model');
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

    public function createUsuario(array $data)
    {
        try {
            if ($this->getEmail($data['email']) == true) {
                return array('status'=>'error', 'message'=>'Esse E-mail já existe cadastrado!');
            } else {
                $sql = "INSERT INTO $this->table (nome, email, senha, data_cadastro) VALUES (?,?,?,?)";
                $this->db->query($sql, [$data['nome'], $data['email'], $data['senha'], $data['data_cadastro']]);
                return array('status'=>'success', 'message' => 'Cadastrado');
            }
        } catch (Exception $e) {
            return array('status'=>'error', 'message' => $e->getMessage());
        }
    }

    public function getEmailSenha($email, $password)
    {
        try {
            $sql = "SELECT id_usuario, nome, email, senha FROM $this->table WHERE email = ?";
            $dados = $this->db->query($sql, [$email]);
            if ($dados->result_array()[0]['email'] && (consultaSenhaCrypty($password, $dados->result_array()[0]['senha']) != true)) {
                return array('status'=>'error', 'message' => 'A senha digitada não confere com a senha Cadastrada!');
            }
            return array('status'=>'success', 'id_usuario' => $dados->result_array()[0]['id_usuario'], 'nome_usuario' => $dados->result_array()[0]['nome']);
        } catch (Exception $e) {
            return array('status'=>'error', 'message' => $e->getMessage());
        }
    }
}
