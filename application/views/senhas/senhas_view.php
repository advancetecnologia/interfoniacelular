<?php
if (!empty($mensagens_sucesso)) {
    ?>
    <div class="alert alert-success" role="alert">
        <?php echo $mensagens_sucesso ?>
    </div>
    <?php
}

if (!empty($mensagens_erro)) {
    ?>
    <div class="alert alert-warning" role="alert">
        <?php echo $mensagens_erro ?>
    </div>
    <?php
}
?>
<div id="result_msg_telefone"></div>
<h3>Senhas de acesso cadastradas</h3><br/><br/>
<a href="<?= base_url('senhas/novo') ?>" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Nova senha</a><br/><br/>