<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Apartamentos_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->model('Blocos_model');
    }

    function get($id_apartamento = NULL, $id_codominio = NULL) {
        if ($id_apartamento !== NULL && $id_codominio !== NULL) {
            $this->db->select('apartamentos.numero_apartamento, apartamentos.id, blocos.nome, blocos.id as bloco_id, apartamentos.ativo, apartamentos.cod_hash');
            $this->db->join('blocos', 'blocos.id = apartamentos.id_bloco', 'left');
            if ($id_apartamento == 0) {
                $this->db->where('apartamentos.id_condominio', $id_codominio);
            } else {
                $this->db->where('apartamentos.id', $id_apartamento);
            }
            $this->db->order_by('blocos.identificador,apartamentos.numero_apartamento');
            $query = $this->db->get('apartamentos');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function valida_apartamento($cod_apartamento) {
        $this->db->where(array('cod_hash' => $cod_apartamento));
        $query = $this->db->get('apartamentos');
        return $query;
    }

    function verifica_existencia($id_bloco = NULL, $apartamento = NULL, $id_apartamento = NULL) {
        if ($id_bloco != NULL && $apartamento != NULL) {
            if ($id_bloco != 0) {
                $this->db->where('id_bloco', $id_bloco);
            } else {
                $this->db->where('id_bloco', NULL);
            }
            if (isset($id_apartamento)) {
                $this->db->where('id !=', $id_apartamento);
            }
            $this->db->where('numero_apartamento', $apartamento);
            $query = $this->db->get('apartamentos');
            if ($query->num_rows() == 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function insert($dados = NULL) {
        if ($dados != NULL) {
            $insert = $this->db->insert('apartamentos', $dados);
            if ($insert) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function update($dados = NULL) {
        if ($dados != NULL) {
            $this->db->trans_start();
            $this->db->where('id', $dados['id']);
            $this->db->update('apartamentos', $dados);
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
            $this->db->trans_start();

	    $this->db->where('id_apartamento', $id);
            $query = $this->db->delete('telefones');

	    $this->db->where('id', $id);
            $query = $this->db->delete('apartamentos');

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

    function get_apartamentos_by_id($id = NULL) {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $query = $this->db->get('apartamentos');
            return $query->result();
        } else {
            redirect(base_url('apartamentos'));
        }
    }

    function ativa_apartamento($id = NULL) {
        if ($id != NULL) {
            $dados = array('ativo' => 1);
            $this->db->where('id', $id);
            $query = $this->db->update('apartamentos', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function desativa_apartamento($id = NULL) {
        if ($id != NULL) {
            $dados = array('ativo' => 0);
            $this->db->where('id', $id);
            $query = $this->db->update('apartamentos', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function esvazia_apartamento($id = NULL, $hash = NULL) {
        if ($id != NULL && $hash != NULL) {
            $this->db->trans_start();

            $this->db->where('id_apartamento', $id);
            $this->db->delete('telefones');

            $this->db->set('ativo', 0);
            $this->db->where('id_apartamento', $id);
            $this->db->update('usuarios');

            $this->db->set('cod_hash', $hash);
            $this->db->where('id', $id);
            $this->db->update('apartamentos');

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

    function delete_selecionados($checkbox = NULL) {
        if ($checkbox != NULL) {
	    $this->db->trans_start();

            $this->db->where_in('id_apartamento', $checkbox);
            $this->db->delete('telefones');

            $this->db->where_in('id', $checkbox);
            $this->db->delete('apartamentos');

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

    function get_qtd_apartamentos($id_condominio = NULL) {
        if ($id_condominio != NULL) {
            $this->db->where('id_condominio', $id_condominio);
            $query = $this->db->get('apartamentos');
            return $query->num_rows();
        } else {
            redirect(base_url('home'));
        }
    }

    public function valida_editar_exluir($id_apartamento, $id_condominio){
        if($id_apartamento != NULL && $id_condominio != NULL){
            $this->db->where('id_condominio', $id_condominio);
            $this->db->where('id', $id_apartamento);
            $query = $this->db->get('apartamentos');
            if ($query->num_rows() == 0) {
                redirect(base_url('home'));
            }
        }else{
            redirect(base_url('home'));
        }
    }
}

?>