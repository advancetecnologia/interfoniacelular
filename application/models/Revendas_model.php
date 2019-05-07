<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Revendas_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function insert($dados = NULL) {
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
            $perfil = 4;
            $this->db->trans_start();
            $query_revenda = "INSERT INTO revendas (nome, cnpj, endereco, "
                . "numero, cidade, estado, cep, telefone, ativo) "
                . "VALUES ('$nome', $cnpj, '$endereco', '$numero', $cidade, $estado, '$cep', '$telefone', 0)";

            $this->db->query($query_revenda);
            $id_revenda = $this->db->insert_id(); 

            $query_usuario = "INSERT INTO usuarios (email, senha, perfil, ativo, id_revenda)"
                    . "VALUES('$email','$senha','$perfil', '0', $id_revenda)";

            $this->db->query($query_usuario);      

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

    function get_revendas() {
        $query = $this->db->get('revendas');
        return $query->result();
    }

    function verifica_existencia_revenda($cnpj = NULL) {
        if ($cnpj != NULL) {
            $this->db->where('cnpj', $cnpj);
            $query = $this->db->get('revendas');
            if ($query->num_rows() == 0) {
                return FALSE;
            } else {
                return TRUE;
            }
        } else {
            redirect(base_url());
        }
    }

    function ativa_revenda($id = NULL) {
        if ($id != NULL) {
            $dados = array('ativo' => 1);
            $this->db->where('id', $id);
            $query = $this->db->update('revendas', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function desativa_revenda($id = NULL) {
        if ($id != NULL) {
            $dados = array('ativo' => 0);
            $this->db->where('id', $id);
            $query = $this->db->update('revendas', $dados);
            if ($query) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            redirect(base_url('home'));
        }
    }

    function get_revenda_by_id($id = NULL){
        if ($id != NULL) {
            $this->db->where('id', $id);
            $query = $this->db->get('revendas');
            return $query->row();
        } else {
            redirect(base_url('home'));
        }
    }

    function set_hash_revenda($id_revenda = NULL, $cnpj = NULL){
        if($id_revenda != NULL && $cnpj != NULL){
            $hash = $this->gera_hash($id_revenda, $cnpj);    
            $data = array('cnpj'=>$cnpj, 'hash'=>$hash, 'id_revenda'=>$id_revenda);
            $this->db->insert('userhash', $data);

            $this->db->select('hash');
            $this->db->where('id_revenda', $id_revenda);
            $query = $this->db->get('userhash');
            return $query->row();
        }else{
            redirect(base_url());
        }
    }

    function gera_hash($id_revenda = NULL, $cnpj = NULL){
        if($id_revenda != NULL && $cnpj != NULL){
            $date = date_create();
            $hash = hash("sha256", $id_revenda . $cnpj . date_timestamp_get($date));
            return $hash;
        }else{
            redirect(base_url()); 
        }
    }

    function update_hash($id_revenda = NULL, $cnpj = NULL){
        if($id_revenda != NULL && $cnpj != NULL){
            $hash = $this->gera_hash($id_revenda, $cnpj);
            $data = array('cnpj'=>$cnpj, 'hash'=>$hash);
            $this->db->where('id_revenda', $id_revenda);
            $this->db->update('userhash', $data);
            $new_hash = $this->get_hash_revenda($id_revenda);
            return $new_hash;
        }else{
            redirect(base_url());
        }
    }

    function get_hash_revenda($id_revenda = NULL){
        if($id_revenda != NULL){
            $this->db->select('hash');
            $this->db->where('id_revenda', $id_revenda);
            $query = $this->db->get('userhash');
            return $query->row();
        }else{
            redirect(base_url());
        }
    }



}

?>