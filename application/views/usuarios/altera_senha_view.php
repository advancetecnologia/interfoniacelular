<?php
if (!empty($mensagens_erro)) {
    ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $mensagens_erro ?>
    </div>
    <?php
}

if (!empty($mensagens_sucesso)) {
    ?>
    <div class="alert alert-success" role="alert">
        <?php echo $mensagens_sucesso ?>
    </div>
    <?php
}
?>
<h3>Alterar senha</h3>
<form class="formularios" method="post" action="<?= base_url('usuarios/update_senha') ?>" data-toggle="validator" role="form">
    <input type="hidden" name="id" value="<?= $id_usuario ?>">
    <div class="panel panel-default">
        <div class="panel-heading">Dados da nova senha</div>
        <div class="panel-body">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Senha atual</div>
                    <input id="textNome" name="senha_atual" class="form-control" placeholder=" Digite sua senha atual" type="password">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Nova senha</div>
                    <input id="textNome" name="senha" class="form-control" placeholder=" Digite a nova senha" type="password">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Repita nova senha</div>
                    <input id="textNome" name="r_senha" class="form-control" placeholder=" Repita a nova senha" type="password">
                </div>
            </div>
        </div>
    </div>
    <button type="submit" onclick="ativa_loading()" class="btn btn-success">Alterar senha</button>
</form>