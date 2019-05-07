<?php
    $id_condominio = $this->session->userdata('id_condominio');
    $nome_condominio = $this->session->userdata('nome_condominio');
    $condominios = $this->session->userdata('condominios');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="manifest" href="/manifest.json"/>
        <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/images/favicon.ico'); ?>"/>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="rating" content="Geral" />
        <meta name="expires" content="never" />
        <meta name="distribution" content="Global" />
        <meta name="robots" content="all" />
        <title>Pináculo | Condomínio Inteligente</title>
        <link rel="stylesheet" href="<?= base_url('assets/css/styleAdmin.css'); ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/css/AdminLTE.css'); ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/css/_all-skins.min.css'); ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.css'); ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap-datepicker.css'); ?>" />
        <script src="<?= base_url('assets/js/variousFunctions.js'); ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/js/jQuery-3.2.1.js'); ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/bootstrap/js/bootstrap.js'); ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/bootstrap/js/bootstrap-datepicker.js'); ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/js/app.js'); ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/js/jquery.maskedinput.js'); ?>" type="text/javascript"></script>
    </head>
    <body class="skin-blue">
        <div id="loading">
            <img src="<?= base_url('assets/images/loading.gif') ?>"><br/>
                Processando....
        </div>
        <div class="wrapper">
            <header class="main-header">
                <a href="<?= base_url('home'); ?>" class="logo"><img src="<?= base_url('assets/images/logo_bco.png'); ?>"></a>
                <nav class="navbar navbar-static-top" role="navigation">
                    <a href="#" class="bnt_menu" data-toggle="offcanvas">
                        <span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span>
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">                     
                            <?php
                            if ($this->session->userdata('perfil') == 3) {
                                ?>
                                <li>
                                    <a href="#">
                                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <span class="hidden-xs"><?php echo $this->session->userdata('usuario'); ?></span>
                                    </a>
                                </li>
                                <?php
                            } else {
                                ?>
                                <li>
                                    <a href="<?= base_url('usuarios/perfil') ?>">
                                        <span class="glyphicon glyphicon-user" aria-hidden="true"></span> <span class="hidden-xs"><?php echo $this->session->userdata('usuario'); ?></span>
                                    </a>
                                </li>
                                <?php
                            }
                            ?>
                            <li>
                                <a href="<?= base_url('sair'); ?>">
                                    <span class="glyphicon glyphicon-off" aria-hidden="true"></span> <span class="hidden-xs">Sair</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <ul class="sidebar-menu">
                        <li class="header">MENU</li>
                        <?php if ($this->session->userdata('perfil') == 1) {
                            ?>
                            <li class="treeview">
                                <a href="<?= base_url('telefones') ?>">
                                    <span>Telefones</span>
                                </a>
                            </li>
                            <?php
                        }
                        ?>

                        <?php if ($this->session->userdata('perfil') == 2 || $this->session->userdata('perfil') == 4 && !empty($id_condominio)) {
                            ?>
                            <li class="treeview">
                                <a href="<?= base_url('telefones') ?>">
                                    <span>Telefones</span>
                                </a>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <span>Cadastros</span>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    if (!$this->session->userdata('bloco_unico')) {
                                        ?>
                                        <li class="active"><a href="<?= base_url('blocos') ?>">Blocos</a></li>
                                        <?php
                                    }
                                    ?>
                                    <li><a href="<?= base_url('apartamentos') ?>">Apartamentos</a></li>
                                    <li><a href="<?= base_url('equipamentos') ?>">Equipamentos</a></li>
                                </ul>
                            </li>
                            <li class="treeview">
                                <a href="#">
                                    <span>Importação de arquivos</span>
                                </a>
                                <ul class="treeview-menu">
                                    <?php
                                    if (!$this->session->userdata('bloco_unico')) {
                                        ?>
                                        <li><a href = "<?= base_url('importacao') ?>">Importação com blocos</a></li>
                                        <?php
                                    }
                                    ?>
                                    <li><a href="<?= base_url('importacao/mc_soft') ?>">Importação padrão MC-Soft</a></li>
                                </ul>
                            </li>
                            <li><a href="<?= base_url('equipamentos/status') ?>">Status de transmissão</a></li>
                            <?php
                        }
                        if ($this->session->userdata('perfil') == 3) {
                            ?>
                            <li class="treeview">
                                <a href="<?= base_url('administrador/clientes') ?>">
                                    <span>Condomínios</span>
                                </a>
                            </li>
                            <li class="treeview">
                                <a href="<?= base_url('administrador/revendas') ?>">
                                    <span>Revendas</span>
                                </a>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </section>
                <?php
                if ($this->session->userdata('perfil') == 4 && !empty($id_condominio)) {
                ?>
                    <div id="footer">
                        <div class="itens_footer">
                            <a onclick = "select_condominio()" href="#"><?=$nome_condominio?>
                            <span class="glyphicon glyphicon-retweet" aria-hidden="true"></span>
                            </a>
                        </div>
                    </div>
                <?php
                }
                ?>  
            </aside>
            <div class="modal fade" id="modal_select_condominio" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title">Selecione o condominio</h4>
                        </div>
                        <div id="list_condominios" class="modal-body">
                            <strong>Selecione o condomínio que deseja gerenciar</strong>
                            <br/><br/>
                            <form method = "post" name="form_select_condominio" action = "<?=base_url('condominio/select_condominio')?>">
                                <div class="form-group">
                                    <div class="input-group">
                                        <div class="input-group-addon">condomínio</div>
                                        <select name="id_condominio" onchange="envia_form_select_condominio()" class="form-control">
                                            <?php
                                            if (count($condominios) > 0) {
                                                ?>
                                                <option value="">Selecione um condomínio</option>
                                                <?php
                                                foreach($condominios as $c){
                                                    ?>
                                                    <option value="<?=$c->id?>">
                                                        <?=$c->nome?>
                                                    </option>
                                                    <?php
                                                }
                                            } else {
                                                ?>
                                                <option value="">Sem condomínios cadastrados cadastrado</option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-wrapper">
                <div id="main">
                    <div id="contents"><?php echo $contents ?></div>
                </div>
            </div> 
        </div>
    </body>
</html>