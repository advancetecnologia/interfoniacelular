<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Telefones_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert($dados = NULL) {
        if ($dados != NULL) {
            $this->db->trans_start();
            $this->db->insert('telefones', $dados);
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

    function update($dados = NULL) {
        if ($dados != NULL) {
            $this->db->trans_start();
            $this->db->where('id', $dados['id']);
            $this->db->update('telefones', $dados);
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

    function existe($dados = NULL) {
        if (isset($dados['id'])) {
            $this->db->where('id !=', $dados['id']);
        }
        if ($dados != NULL) {
            $this->db->where('telefone', $dados['telefone']);
            $this->db->where('id_apartamento', $dados['id_apartamento']);
            $query = $this->db->get('telefones');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function cota($dados = NULL) {
        if ($dados != NULL) {
            $this->db->where('id_apartamento', $dados['id_apartamento']);
            $query = $this->db->get('telefones');
            if ($query->num_rows() < 2) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function cota_memoria($dados = NULL) {
        if ($dados != NULL) {
            $this->db->where('id_apartamento', $dados['id_apartamento']);
            $this->db->where('memoria', 1);
            $query = $this->db->get('telefones');
            if ($query->num_rows() < 2) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function get_condominios($id = NULL) {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $this->db->where('ativo', 1);
            $this->db->select('id,nome');
            $query = $this->db->get('condominios');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function get_telefones($id_apartamento = NULL, $id_codominio = NULL) {
        if ($id_apartamento !== NULL && $id_codominio !== NULL) {
            if ($id_apartamento == 0) {
                $this->db->where('id_condominio', $id_codominio);
            } else {
                $this->db->where('id_apartamento', $id_apartamento);
            }
            $this->db->select('id,nome,telefone,ordem,ativo,ordem,memoria');
            $this->db->order_by('ordem');
            $query = $this->db->get('telefones');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function insert_faixa($dados = NULL) {
        if ($dados != NULL) {
            $dados['faixa_padrao'] = 0;
            $this->db->trans_start();
            $this->db->insert('faixas_telefones', $dados);
            $this->db->where('id_telefone', $dados['id_telefone']);
            $this->db->where('faixa_padrao', 1);
            $this->db->delete('faixas_telefones');
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

    function insert_faixa_padrao($id = NULL) {
        if ($id != NULL) {
            $dados = array('id_telefone' => $id, 'inicio' => '00:00:00', 'fim' => '23:59:59',
                'seg' => 1, 'ter' => 1, 'qua' => 1, 'qui' => 1, 'sex' => 1, 'sab' => 1, 'dom' => 1, 'ativo' => 1, 'faixa_padrao' => 1);
            $this->db->trans_start();
            $this->db->insert('faixas_telefones', $dados);
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

    function get_faixas($id_telefone = NULL, $tipo = NULL) {
        if ($id_telefone != NULL) {
            $this->db->where('id_telefone', $id_telefone);
            if ($tipo == 1) {
                $this->db->where('faixa_padrao', 0);
            }
            $query = $this->db->get('faixas_telefones');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function delete_faixa($id_faixa = NULL) {
        if ($id_faixa != NULL) {
            $this->db->where('id', $id_faixa);
            $delete = $this->db->delete('faixas_telefones');
            if ($delete) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function get_quantidade_telefones($dados = NULL) {
        if ($dados != NULL) {
            $this->db->where('id_apartamento', $dados['id_apartamento']);
            $this->db->select('id');
            $query = $this->db->get('telefones');
            return $query->num_rows();
        } else {
            redirect(base_url('home'));
        }
    }

    function ativa_telefone($id = NULL) {
        if ($id != NULL) {
            $dados = array('ativo' => 1);
            $this->db->where('id', $id);
            $query = $this->db->update('telefones', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function desativa_telefone($id = NULL) {
        if ($id != NULL) {
            $dados = array('ativo' => 0);
            $this->db->where('id', $id);
            $query = $this->db->update('telefones', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function get_ordem($id = NULL) {
        if ($id != NULL) {
            $this->db->select('ordem');
            $this->db->where('id', $id);
            $query = $this->db->get('telefones');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function ultimo_telefone($id_apartamento = NULL) {
        if ($id_apartamento != NULL) {
            $this->db->select('MAX(ordem) as ultimo');
            $this->db->where('id_apartamento', $id_apartamento);
            $query = $this->db->get('telefones');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function telefone_anterior($ordem = NULL, $id_telefone) {
        if ($ordem != NULL && $id_telefone != NULL) {
            $this->db->select('id');
            $this->db->where('id_apartamento', $id_telefone);
            $this->db->where('ordem', $ordem - 1);
            $query = $this->db->get('telefones');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function telefone_posterior($ordem = NULL, $id_telefone) {
        if ($ordem != NULL && $id_telefone != NULL) {
            $this->db->select('id');
            $this->db->where('id_apartamento', $id_telefone);
            $this->db->where('ordem', $ordem + 1);
            $query = $this->db->get('telefones');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function altera_ordem_up($id = NULL, $ordem_atual = NULL, $id_apartamento) {
        if ($id != NULL && $ordem_atual != NULL) {
            $dados = array('ordem' => $ordem_atual - 1);
            $this->db->where('id', $id);
            $query = $this->db->update('telefones', $dados);
            if ($query) {
//                $this->db->query("CALL reinsert_apart($id_apartamento)");
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function altera_ordem_down($id = NULL, $ordem_atual = NULL, $id_apartamento) {
        if ($id != NULL && $ordem_atual != NULL) {
            $dados = array('ordem' => $ordem_atual + 1);
            $this->db->where('id', $id);
            $query = $this->db->update('telefones', $dados);
            if ($query) {
//                $this->db->query("CALL reinsert_apart($id_apartamento)");
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function altera_ordem_direto($id_telefone = NULL, $ordem = NULL) {
        if ($id_telefone != NULL && $ordem != NULL) {
            $dados = array('ordem' => $ordem);
            $this->db->where('id', $id_telefone);
            $this->db->update('telefones', $dados);
        } else {
            redirect(base_url('home'));
        }
    }

    function delete($id = NULL) {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $query = $this->db->delete('telefones');
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function get_qtd_telefones($id_apartamento = NULL) {
        if ($id_apartamento != NULL) {
            $this->db->where('id_apartamento', $id_apartamento);
            $query = $this->db->get('telefones');
            return $query->num_rows();
        } else {
            redirect(base_url('home'));
        }
    }

    function get_telefones_by_id($id = NULL) {
        if ($id != NULL) {
            $this->db->where('id', $id);
            $query = $this->db->get('telefones');
            return $query->result();
        } else {
            redirect(base_url('telefones'));
        }
    }

    function get_transmissao($telefone = NULL, $apartamento = NULL, $id_apartamento) {
        if ($telefone != NULL && $apartamento != NULL) {
            $content = $telefone . ";" . $apartamento;
            $query = $this->db->query("select sended, fault from changes where change_content = '$content' and change_type=13 order by changeid desc limit 1");
            if ($query->num_rows() > 0) {
                return $query->result()[0]->sended;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    public function delete_telefones_by_apartamento($id_apartamento) {
        $this->db->where('id_apartamento', $id_apartamento);
        $query = $this->db->delete('telefones');
        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function valida_edicao_exlcusao($id_telefone, $id_apartamento){
        if($id_telefone != NULL && $id_apartamento != NULL){
            $this->db->where('id_apartamento', $id_apartamento);
            $this->db->where('id', $id_telefone);
            $query = $this->db->get('telefones');
            if ($query->num_rows() == 0) {
                redirect(base_url('home'));
            }
        }else{
            redirect(base_url('home'));
        }
    }

}

?>
