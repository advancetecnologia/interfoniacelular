<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Equipamentos_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert($dados = NULL) {
        if ($dados != NULL) {
            $insert = $this->db->insert('equipamentos', $dados);
            if ($insert) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function get($id_codominio = NULL) {
        if ($id_codominio !== NULL) {
            $this->db->select('equipamentos.id, equipamentos.imei, equipamentos.qtd_digitos, '
                    . 'equipamentos.equip_portaria, equipamentos.nome, equipamentos.ativo, blocos.nome as bloco');
            $this->db->join('blocos', 'blocos.id = equipamentos.id_bloco', 'left');
            $this->db->where('equipamentos.id_condominio', $id_codominio);
            $query = $this->db->get('equipamentos');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function get_transmissao($id_codominio = NULL) {
        if ($id_codominio !== NULL) {
            $this->db->select('equipamentos.id, equipamentos.nome, ultima_comunicacao.data');
            $this->db->join('ultima_comunicacao', 'ultima_comunicacao.id_equipamento = equipamentos.id', 'left');
            $this->db->where('equipamentos.id_condominio', $id_codominio);
            $this->db->where('equipamentos.ativo', 1);
            $query = $this->db->get('equipamentos');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function verifica_existencia($imei = NULL, $id_equipamento = NULL) {
        if ($imei != NULL) {
            $this->db->where('imei', $imei);
            if (isset($id_equipamento)) {
                $this->db->where('id !=', $id_equipamento);
            }
            $query = $this->db->get('equipamentos');
            if ($query->num_rows() == 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function get_equipamento_by_id($id = NULL) {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $query = $this->db->get('equipamentos');
            return $query->result();
        } else {
            redirect(base_url('equipamentos'));
        }
    }

    function update($dados = NULL) {
        if ($dados != NULL) {
            $this->db->trans_start();
            $this->db->where('id', $dados['id']);
            $this->db->update('equipamentos', $dados);
            $this->db->trans_complete();
            if ($this->db->trans_status()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function delete($id = NULL) {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $query = $this->db->delete('equipamentos');
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function ativa_equipamento($id = NULL) {
        if ($id != NULL) {
            $dados = array('ativo' => 1);
            $this->db->where('id', $id);
            $query = $this->db->update('equipamentos', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function desativa_equipamento($id = NULL) {
        if ($id != NULL) {
            $dados = array('ativo' => 0);
            $this->db->where('id', $id);
            $query = $this->db->update('equipamentos', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function get_pendende_trasmissao($id = NULL) {
        if ($id != NULL) {
            $where = "id_equipamento=" . $id . " AND NOT (sended=1 OR fault=1)";
            $this->db->where($where);
            $query = $this->db->get('changes');
            return $query->num_rows();
        } else {
            redirect(base_url('home'));
        }
    }

    function get_ultima_comunicacao($id_equipamento = NULL){
        if($id_equipamento != NULL){
            $this->db->select('data');
            $this->db->where('id_equipamento', $id_equipamento);
            $query = $this->db->get('ultima_comunicacao');
            return $query->row();
        }else{
            redirect(base_url('home'));
        }
    }

    public function valida_editar_excluir($id_equipamento, $id_condominio){
        if($id_equipamento != NULL && $id_condominio != NULL){
            $this->db->where('id_condominio', $id_condominio);
            $this->db->where('id', $id_equipamento);
            $query = $this->db->get('equipamentos');
            if ($query->num_rows() == 0) {
                redirect(base_url('home'));
            }
        }else{
            redirect(base_url('home'));
        }
    }
}

?>