<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Senhas_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function verifica_existencia($senha = NULL) {
        if ($senha != NULL) {
            $this->db->where('senha', $senha);
            $query = $this->db->get('senhas_acesso');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect('home');
        }
    }

    function insert($dados = NULL) {
        if ($dados != NULL) {
            $this->db->trans_start();
            $this->db->insert('senhas_acesso', $dados);
            $id = $this->db->query('SELECT LAST_INSERT_ID() as id FROM telefones LIMIT 1');
            $this->db->trans_complete();
            if ($this->db->trans_status()) {
                return $id->result()[0]->id;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function cotas($dados = NULL) {
        if ($dados != NULL) {
            $this->db->where('id_condominio', $dados['id_condominio']);
            $this->db->where('id_apartamento', $dados['id_apartamento']);
            $query = $this->db->get('senhas_acesso');
            if ($query->num_rows() < 10) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

}
