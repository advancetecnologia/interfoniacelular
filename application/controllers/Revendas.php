<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Revendas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('logado');
        $this->logado->get_logado();
        $this->logado->verifica_permissoes(array('4'));
        $this->load->model('Revendas_model');
    }

    public function new_hash(){
        $hash = $this->Revendas_model->update_hash($this->input->get('id_revenda'), $this->input->get('cnpj_revenda'));
        echo $hash->hash;
    }

}
