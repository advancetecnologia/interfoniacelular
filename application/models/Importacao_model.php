<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Importacao_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert_bloco($bloco) {
        $dados['id_condominio'] = $this->session->userdata('id_condominio');
        $dados['nome'] = "Bloco - " . $bloco;
        $dados['identificador'] = $bloco;
        $dados['ativo'] = 1;

        $insert = $this->db->insert('blocos', $dados);
        if ($insert) {
            $retorno['return'] = TRUE;
            $retorno['id_bloco'] = $this->db->insert_id();
        } else {
            $retorno['return'] = FALSE;
        }

        return $retorno;
    }

    function insert_apartamento($apartamento, $bloco) {
        $dados['id_condominio'] = $this->session->userdata('id_condominio');
        $dados['id_bloco'] = $bloco;
        $dados['numero_apartamento'] = $apartamento;
        $dados['ativo'] = 1;
        $dados['cod_hash'] = $this->gera_hash($dados['id_condominio'], $bloco, $apartamento);
        $insert = $this->db->insert('apartamentos', $dados);
        if ($insert) {
            $retorno['return'] = TRUE;
            $retorno['id_apartamento'] = $this->db->insert_id();
        } else {
            $retorno['return'] = FALSE;
        }
        return $retorno;
    }

    function insert_telefone($id_apartamento, $ordem, $telefone1, $telefone2 = NULL) {
        $dados['id_apartamento'] = $id_apartamento;
        $dados['nome'] = $telefone1;
        $dados['telefone'] = $telefone1;
        $dados['ordem'] = $ordem;
        $dados['ativo'] = 1;
        $dados['id_condominio'] = $this->session->userdata('id_condominio');
        $dados['memoria'] = 1;
        $this->db->insert('telefones', $dados);
        $id_telefone1 = $this->db->insert_id();
        $telefone1_faixa = array('id_telefone' => $id_telefone1, 'inicio' => '00:00:00', 'fim' => '23:59:59',
            'seg' => 1, 'ter' => 1, 'qua' => 1, 'qui' => 1, 'sex' => 1, 'sab' => 1, 'dom' => 1, 'ativo' => 1, 'faixa_padrao' => 1);

        $this->db->insert('faixas_telefones', $telefone1_faixa);

        if ($telefone2 != NULL) {
            $dados['nome'] = $telefone2;
            $dados['telefone'] = $telefone2;
            $dados['ordem'] = 2;
            $this->db->insert('telefones', $dados);
            $id_telefone2 = $this->db->insert_id();
            $telefone2_faixa = array('id_telefone' => $id_telefone2, 'inicio' => '00:00:00', 'fim' => '23:59:59',
                'seg' => 1, 'ter' => 1, 'qua' => 1, 'qui' => 1, 'sex' => 1, 'sab' => 1, 'dom' => 1, 'ativo' => 1, 'faixa_padrao' => 1);

            $this->db->insert('faixas_telefones', $telefone2_faixa);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status()) {
            $retorno = TRUE;
            $this->db->trans_commit();
        } else {
            $retorno = FALSE;
            $this->db->trans_rollback();
        }
        return $retorno;
    }

    public function gera_hash($id_condominio, $id_bloco, $apartamento) {
        $time = time();
        $hash = $id_condominio . $id_bloco . $apartamento . $time;
        $hash_hexa = dechex((float) $hash);
        return $hash_hexa;
    }

    public function verifica_exitencia_bloco($bloco) {
        $this->db->select('id');
        $this->db->where('id_condominio', $this->session->userdata('id_condominio'));
        $this->db->where('identificador', $bloco);
        $query = $this->db->get('blocos');
        $result = $query->result();
        $rows = $query->num_rows();
        $retorno = "";

        if ($rows > 0) {
            $retorno['id_bloco'] = $result[0]->id;
            $retorno['return'] = TRUE;
        } else {
            $retorno['return'] = FALSE;
        }

        return $retorno;
    }

    public function verifica_exitencia_apartamento($apartamento, $bloco) {
        $this->db->select('id');
        $this->db->where('id_bloco', $bloco);
        $this->db->where('numero_apartamento', $apartamento);
        $query = $this->db->get('apartamentos');
        $result = $query->result();
        $rows = $query->num_rows();
        $retorno = "";

        if ($rows > 0) {
            $retorno['id_apartamento'] = $result[0]->id;
            $retorno['return'] = TRUE;
        } else {
            $retorno['return'] = FALSE;
        }
        return $retorno;
    }

//    public function delete_telefones($id_apartamento) {
//        $this->db->where('id_apartamento', $id_apartamento);
//        $query = $this->db->delete('telefones');
//        if ($query) {
//            return TRUE;
//        } else {
//            return FALSE;
//        }
//    }

    public function get_telefone_by_apartamento($id_apartamento) {
        $this->db->where('id_apartamento', $id_apartamento);
        $query = $this->db->get('telefones');
        $rows = $query->num_rows();
        if ($rows > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>