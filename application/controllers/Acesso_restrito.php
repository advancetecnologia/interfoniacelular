<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Acesso_restrito extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->template->load('template_inicial', 'acesso_restrito/acesso_restrito_view');
    }

}
