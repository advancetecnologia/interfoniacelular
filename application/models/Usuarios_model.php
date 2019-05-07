<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usuarios_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function get_usuario($usuario) {
        $this->db->where(array('email' => $usuario));
        $query = $this->db->get('usuarios');
        return $query->num_rows();
    }

    function get_usuario_login($usuario, $senha) {
        $this->db->where(array('email' => $usuario, 'senha' => $senha));
        $this->db->where('ativo', 1);
        $query = $this->db->get('usuarios');
        return $query->result();
    }

    function get_revenda($usuario, $senha) {
        $this->db->where(array('email' => $usuario, 'senha' => $senha));
        $this->db->where('ativo', 1);
        $this->db->where('perfil', 4);
        $this->db->where('id_revenda !=', NULL);
        $this->db->select('id_revenda');
        $query = $this->db->get('usuarios');
        $return['result'] = $query->row();
        $return['num_rows'] = $query->num_rows();
        return $return;
    }

    function insert($dados = NULL) {
        if ($dados != NULL) {
            $id_condominio = $dados['id_condominio'];
            $id_apartamento = $dados['id_apartamento'];
            $senha = $dados['senha'];
            $email = $dados['email'];

            if (!isset($dados['id_bloco'])) {
                $query_usuario = "INSERT INTO usuarios (id_condominio, id_apartamento, senha, email, perfil, ativo, primeiro_acesso) "
                        . "VALUES ($id_condominio, $id_apartamento, '$senha', '$email', 1, 1, 1 )";
            } else {
                $id_bloco = $dados['id_bloco'];
                $query_usuario = "INSERT INTO usuarios (id_condominio, id_bloco, id_apartamento, senha, email, perfil, ativo, primeiro_acesso) "
                        . "VALUES ($id_condominio, $id_bloco, $id_apartamento, '$senha', '$email', 1, 1, 1 )";
            }
            $this->db->trans_start();
            $this->db->query($query_usuario);

            $query_apartamento = "UPDATE apartamentos SET ativo = 1 WHERE id = $id_apartamento";
            $this->db->query($query_apartamento);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return TRUE;
            }
        } else {
            redirect(base_url());
        }
    }

    function get_dados_usuario($id) {
        $this->db->select('email, perfil, id_revenda');
        $this->db->where('id', $id);
        $this->db->where('ativo', 1);
        $query = $this->db->get('usuarios');
        return $query->result();
    }
    
    function update_senha($dados = NULL) {
        if ($dados != NULL) {
            $this->db->trans_start();
            $this->db->where('id', $dados['id']);
            $this->db->update('usuarios', $dados);
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

    function valida_senha_atual($id = NULL, $senha = NULL) {
        if ($id != NULL && $senha != NULL) {
            $this->db->select('senha');
            $this->db->where('id', $id);
            $query = $this->db->get('usuarios');
            $senha_banco = $query->result();
            if ($senha == $senha_banco[0]->senha) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function insert_url($id = NULL, $hash = NULL) {
        if ($id != NULL && $hash != NULL) {
            $dados = array('id_usuario' => $id, 'hash' => $hash);
            $this->db->trans_start();
            $this->db->where('id', $id);
            $this->db->insert('esqueceu_senha', $dados);
            $this->db->trans_complete();
            if ($this->db->trans_status()) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    function verifica_hash($hash = NULL) {
        if ($hash != NULL) {
            $this->db->select('id_usuario');
            $this->db->where('hash', $hash);
            $query = $this->db->get('esqueceu_senha');
            return $query->result();
        } else {
            redirect(base_url());
        }
    }

    function get_id_usuario($email = NULL) {
        if ($email != NULL) {
            $this->db->select('id');
            $this->db->where('email', $email);
            $query = $this->db->get('usuarios');
            return $query->result();
        } else {
            redirect(base_url());
        }
    }

    function delete_url($id = NULL) {
        if ($id != NULL) {
            $this->db->where('id_usuario', $id);
            $query = $this->db->delete('esqueceu_senha');
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url());
        }
    }

    function ativa_usuario($id_condominio = NULL) {
        if ($id_condominio != NULL) {
            $dados = array('ativo' => 1);
            $this->db->where('id_condominio', $id_condominio);
            $query = $this->db->update('usuarios', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function desativa_usuario($id_condominio = NULL) {
        if ($id_condominio != NULL) {
            $dados = array('ativo' => 0);
            $this->db->where('id_condominio', $id_condominio);
            $query = $this->db->update('usuarios', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }
    
    function ativa_usuario_by_revenda($id_revenda = NULL) {
        if ($id_revenda != NULL) {
            $dados = array('ativo' => 1);
            $this->db->where('id_revenda', $id_revenda);
            $query = $this->db->update('usuarios', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function desativa_usuario_by_revenda($id_revenda = NULL) {
        if ($id_revenda != NULL) {
            $dados = array('ativo' => 0);
            $this->db->where('id_revenda', $id_revenda);
            $query = $this->db->update('usuarios', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function get_primeiro_acesso() {
        $id = $this->session->userdata('id_usuario');
        $this->db->select('primeiro_acesso');
        $this->db->where(array('id' => $id));
        $this->db->where(array('perfil' => 2));
        $query = $this->db->get('usuarios');
        return $query->result();
    }

    function remove_primeiro_acesso() {
        $id = $this->session->userdata('id_usuario');
        $dados = array('primeiro_acesso' => 0);
        $this->db->where('id', $id);
        $query = $this->db->update('usuarios', $dados);
        if ($query) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}

?>