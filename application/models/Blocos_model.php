<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Blocos_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert($dados = NULL, $bloco_unico) {
        if ($dados != NULL) {
            $existe = $this->verifica_existencia($dados);
            if ($existe) {
                return 2;
            } else if ($dados['identificador'] == 0) {
                return 3;
            } else if ($bloco_unico) {
                if ($this->restricoes_apartamento($bloco_unico)) {
                    if ($this->restricoes_equipamentos($bloco_unico)) {
                        $insert = $this->db->insert('blocos', $dados);
                        if ($insert) {
                            return 1;
                        } else {
                            return 0;
                        }
                    } else {
                        return 4;
                    }
                } else {
                    return 5;
                }
            } else {
                $insert = $this->db->insert('blocos', $dados);
                if ($insert) {
                    return 1;
                } else {
                    return 0;
                }
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function update($dados = NULL) {
        if ($dados != NULL) {
            $existe = $this->verifica_existencia($dados);
            if ($existe) {
                return 2;
            } else {
                $this->db->where('id', $dados['id']);
                $update = $this->db->update('blocos', $dados);
                if ($update) {
                    return 1;
                } else {
                    return 0;
                }
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function delete($id = NULL) {
        if ($id != NULL) {
            if ($this->restricoes_apartamento($id)) {
                if ($this->restricoes_equipamentos($id)) {
                    $this->db->where('id', $id);
                    $query = $this->db->delete('blocos');
                    if ($query) {
                        return 1;
                    } else {
                        return 0;
                    }
                } else {
                    return 3;
                }
            } else {
                return 2;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function restricoes_apartamento($id = NULL) {
        if ($id != NULL) {
            $this->db->where('id_bloco', $id);
            $query = $this->db->get('apartamentos');
            $qtd = $query->num_rows();
            if ($qtd > 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function restricoes_equipamentos($id = NULL) {
        if ($id != NULL) {
            $this->db->where('id_bloco', $id);
            $query = $this->db->get('equipamentos');
            $qtd = $query->num_rows();
            if ($qtd > 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function verifica_existencia($dados) {
        if (!empty($dados['id'])) {
            $this->db->where('id !=', $dados['id']);
        }
        $this->db->where('id_condominio', $dados['id_condominio']);
        $this->db->where('identificador', $dados['identificador']);
        $query = $this->db->get('blocos');
        if ($query->num_rows() == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function get_by_condominio($id_condominio = NULL) {
        if ($id_condominio != NULL) {
            $this->db->select('id,nome,identificador');
            $this->db->where('id_condominio', $id_condominio);
            $this->db->order_by('identificador');
            $query = $this->db->get('blocos');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function get_bloco_by_id($id = NULL) {
        if ($id != NULL) {
            $this->db->select('id,nome,identificador');
            $this->db->where('id', $id);
            $this->db->order_by('identificador');
            $query = $this->db->get('blocos');
            return $query->result();
        } else {
            redirect(base_url('home'));
        }
    }

    function get_bloco_unico($id_condominio = NULL) {
        if ($id_condominio != NULL) {
            $this->db->where('id_condominio', $id_condominio);
            $this->db->where('identificador', 0);
            $query = $this->db->get('blocos');
            if ($query->num_rows() > 0) {
                return $query->result()[0]->id;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    public function valida_editar_excluir($id_bloco, $id_condominio){
        if($id_bloco != NULL && $id_condominio != NULL){
            $this->db->where('id_condominio', $id_condominio);
            $this->db->where('id', $id_bloco);
            $query = $this->db->get('blocos');
            if ($query->num_rows() == 0) {
                redirect(base_url('home'));
            }
        }else{
            redirect(base_url('home'));
        }
    }

}

?>