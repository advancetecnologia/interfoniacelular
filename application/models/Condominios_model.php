<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Condominios_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert($dados = NULL, $id_revenda = NULL) {
        if ($dados != NULL) {
            $nome = $dados["nome"];
            $cnpj = $dados['cnpj'];
            $endereco = $dados['rua'];
            $numero = $dados['numero'];
            $cidade = $dados['cidade'];
            $estado = $dados['estado'];
            $cep = $dados['cep'];
            $telefone = $dados['telefone'];
            $email = $dados['email'];
            $senha = md5($dados['senha']);
            $perfil = 2;

            if($id_revenda == NULL){
                $query_condominio = "INSERT INTO condominios (nome, cnpj, endereco, "
                    . "numero, cidade, estado, cep, telefone,ativo) "
                    . "VALUES ('$nome', $cnpj, '$endereco', '$numero', $cidade, $estado, '$cep', '$telefone', 1)";
            }else{
                $query_condominio = "INSERT INTO condominios (nome, cnpj, endereco, "
                    . "numero, cidade, estado, cep, telefone,ativo, id_revenda) "
                    . "VALUES ('$nome', $cnpj, '$endereco', '$numero', $cidade, $estado, '$cep', '$telefone', 1, $id_revenda)";
            }
   
            $this->db->trans_start();
            $this->db->query($query_condominio);
            $id_condominio = $this->db->insert_id();
            $query_usuario = "INSERT INTO usuarios (id_condominio, email, senha, perfil, ativo, primeiro_acesso)"
                    . "VALUES('$id_condominio','$email','$senha','$perfil', '1', 1)";

            if($id_revenda == NULL){
                $this->db->query($query_usuario);
            }        

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

    function verifica_existencia_condominio($cnpj = NULL) {
        if ($cnpj != NULL) {
            $this->db->where('cnpj', $cnpj);
            $query = $this->db->get('condominios');
            if ($query->num_rows() == 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            redirect(base_url());
        }
    }

    function get_condominios() {
        $this->db->select('id, nome, cnpj, telefone, ativo');
        $query = $this->db->get('condominios');
        return $query->result();
    }

    function get_condominios_by_revenda($id_revenda = NULL){
        if($id_revenda != NULL){
            $this->db->select('id, nome, cnpj');
            $this->db->where('id_revenda', $id_revenda);
            $this->db->where('ativo', 1);
            $query = $this->db->get('condominios');
            return $query->result();
        }else{
            redirect(base_url());
        }
    }

    function get_hash ($id_condominio = NULL){
        if($id_condominio != NULL){
            $this->db->select('hash');
            $this->db->where('id_condominio', $id_condominio);
            $query = $this->db->get('userhash');
            $return['num_rows'] = $query->num_rows();
            $return['data'] = $query->row();
            return $return;
        }else{
            redirect(base_url());
        }
    }

    function set_hash($id_condominio = NULL, $cnpj = NULL){
        if($id_condominio != NULL && $cnpj != NULL){
            $hash = $this->gera_hash($id_condominio, $cnpj);
            $data = array('cnpj'=>$cnpj, 'hash'=>$hash, 'id_condominio'=>$id_condominio);
            $this->db->insert('userhash', $data);

            $new_hash = $this->get_hash($id_condominio);
            return $new_hash;
        }else{
            redirect(base_url());
        }
    }

    function update_hash($id_condominio = NULL, $cnpj = NULL){
        if($id_condominio != NULL && $cnpj != NULL){
            $hash = $this->gera_hash($id_condominio, $cnpj);
            $data = array('hash'=>$hash);
            $this->db->where('id_condominio', $id_condominio);
            $this->db->where('cnpj', $cnpj);
            $this->db->update('userhash', $data);
            $new_hash = $this->get_hash($id_condominio);
            return $new_hash;
        }else{
            redirect(base_url());
        }
    }

    function gera_hash($id_condominio = NULL, $cnpj = NULL){
        if($id_condominio != NULL && $cnpj != NULL){
            $date = date_create();
            $hash = hash("sha256", $id_condominio . $cnpj . date_timestamp_get($date));
            return $hash;
        }else{
            redirect(base_url()); 
        }
    }

    function ativa_condominio($id = NULL) {
        if ($id != NULL) {
            $dados = array('ativo' => 1);
            $this->db->where('id', $id);
            $query = $this->db->update('condominios', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function desativa_condominio($id = NULL) {
        if ($id != NULL) {
            $dados = array('ativo' => 0);
            $this->db->where('id', $id);
            $query = $this->db->update('condominios', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function set_bloco_unico($id_condominio = NULL) {
        if ($id_condominio != NULL) {
            $dados = array('bloco_unico' => 1, 'quantidade_digitos' => 0);
            $this->db->where('id', $id_condominio);
            $update = $this->db->update('condominios', $dados);
            if ($update) {
                $cadastra_bloco_unico = $this->cadastra_bloco_unico($id_condominio);
                if ($cadastra_bloco_unico) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function cadastra_bloco_unico($id_condominio = NULL) {
        if ($id_condominio != NULL) {
            $dados = array('id_condominio' => $id_condominio, 'nome' => 'Bloco único', 'ativo' => 1, 'identificador' => 0);
            $insert = $this->db->insert('blocos', $dados);
            if ($insert) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function set_blocos($id_condominio = NULL) {
        if ($id_condominio != NULL) {
            $dados = array('bloco_unico' => 0);
            $this->db->where('id', $id_condominio);
            $update = $this->db->update('condominios', $dados);
            if ($update) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function get_bloco_unico() {
        $this->db->select('bloco_unico');
        $this->db->where('id', $this->session->userdata('id_condominio'));
        $query = $this->db->get('condominios');
        return $query->row();
    }

    function get_nome_condominio($id = NULL){
        if($id != NULL){
            $this->db->select('nome');
            $this->db->where('id', $id);
            $query = $this->db->get('condominios');
            return $query->row();
        }else{
            redirect(base_url('home'));
        }
    }

    function get_condominio($id = NULL){
        if($id != NULL){
            $this->db->where('id', $id);
            $query = $this->db->get('condominios');
            return $query->row();
        }else{
            redirect(base_url('home'));
        }
    }

}

?>