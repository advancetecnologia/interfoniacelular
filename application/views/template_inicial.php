<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="pt-br" xml:lang="pt-br">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"></meta> 
        <link rel="manifest" href="manifest.json"/>
        <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/images/favicon.ico'); ?>"/>
        <meta name="description" content="" />
        <meta name="keywords" content="" />
        <meta name="rating" content="Geral" />
        <meta name="expires" content="never" />
        <meta name="distribution" content="Global" />
        <meta name="robots" content="all" />
        <title>Pináculo | Condomínio Inteligente</title>
        <link rel="stylesheet" href="<?= base_url('assets/css/styleAdmin.css'); ?>" />
        <link rel="stylesheet" href="<?= base_url('assets/bootstrap/css/bootstrap.css'); ?>" />
        <script src="<?= base_url('assets/bootstrap/js/bootstrap.js'); ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/js/variousFunctions.js'); ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/js/jQuery-3.2.1.js'); ?>" type="text/javascript"></script>
        <script src="<?= base_url('assets/js/jquery.maskedinput.js'); ?>" type="text/javascript"></script>
    </head>
    <body>
        <div id="loading">
            <img src="<?= base_url('assets/images/loading.gif') ?>"><br/>
                Processando....
        </div>
        <?php
        if ($this->session->flashdata('falha')) {
            ?>
            <div class="alert alert-danger msg_alertas" role="alert">
                <?php
                echo $this->session->flashdata('falha');
                ?>
            </div>
            <?php
        }
        if ($this->session->flashdata('sucesso')) {
            ?>
            <div class="alert alert-success msg_alertas" role="alert">
                <?php
                echo $this->session->flashdata('sucesso');
                ?>
            </div>
            <?php
        }
        ?>
        <div id="main">
            <div class="login-page">
                <div id="contents"><?php echo $contents ?></div>
            </div>
        </div>
    </body>
</html>