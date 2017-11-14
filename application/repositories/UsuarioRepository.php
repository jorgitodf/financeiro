<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once __DIR__ . '/../models/Usuario_model.php';
require_once __DIR__ . '/../../system/core/Model.php';

class UsuarioRepository extends CI_Model
{
    protected $usuario_model;

    public function __construct()
    {
        $this->usuario_model = new Usuario_model();
        $this->load->database();
        $this->load->helper("funcoes");
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function getAllUsuarios()
    {
        $usuarios = $this->db->get($this->usuario_model->getTable());
        return $usuarios->result_array();
    }

    public function createUsuario(array $data)
    {
        if ($this->getEmail($data['email']) == true) {
            return array('status'=>'error', 'message'=>'Esse E-mail já existe cadastrado!');
        } else {
            $sql = "INSERT INTO {$this->usuario_model->getTable()} (nome, email, senha, data_cadastro) VALUES (?,?,?,?)";
            $this->db->query($sql, [stripHTMLtags($data['nome']), $data['email'], cryptySenha($data['senha']), date("Y-m-d H:i:s")]);
            if ($this->db->affected_rows() > 0) {
                return array('status'=>'success', 'message' => 'Usuário Cadastrado com Sucesso!');
            } else {
                return array('status'=>'error', 'message' => $this->db->error()["message"]);
            }
        }
    }
    
    public function getEmail($email)
    {
        $usuario = $this->db->get_where($this->usuario_model->getTable(), ['email' => $email]);
        return $usuario->custom_row_object(0, 'Usuario_model');
    }
}    