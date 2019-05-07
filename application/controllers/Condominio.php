<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Condominio extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('logado');
        $this->logado->get_logado();
        $this->logado->verifica_permissoes(array('2','4'));
        $this->load->model('Condominios_model');
        $this->load->model('Usuarios_model');
    }

    public function select_condominio() {
        $this->session->set_userdata($this->input->post());
        $nome_condominio = $this->Condominios_model->get_nome_condominio($this->input->post('id_condominio'));
        $this->session->set_userdata(array('nome_condominio'=>$nome_condominio->nome));
        redirect('home');
    }

    public function new_hash(){
        $hash = $this->Condominios_model->update_hash($this->input->get('id_condominio'), $this->input->get('cnpj'));
        echo $hash['data']->hash;
    }

}
