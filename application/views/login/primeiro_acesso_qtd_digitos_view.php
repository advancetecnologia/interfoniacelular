<div class="painel_primeiro_acesso">
    <div id="form_login_top">
        <div id="form_login_top_esq"><span class="title_login_bold">Condomínio </span><span class="title_login_light">Inteligente</span></div>
        <div id="form_login_top_dir"><img src="<?= base_url('assets/images/logo.jpg'); ?>"></div>
    </div>
    <div id="error_primeiro_acesso">
        <?php
        if (!empty($mensagens_erro)) {
            ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $mensagens_erro ?>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="text_explicativo">
        No seu condomínio qual é a quantidade máxima de dígitos dos apartamentos?<br/><br/>
    </div>
    <form id="form_novo_usuario" method="post" action="<?= base_url('primeiro_acesso/set_blocos') ?>">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Quantidade de dígitos</div>
                <input id="textNome" name="qtd_digitos" class="form-control" placeholder="Digite quantidade máxima de dígitos dos apartamentos" type="text">
            </div>
        </div>
        <button type="submit" onclick="ativa_loading()" class="btn btn-success">Salvar</button>
        <a href="<?= base_url('primeiro_acesso') ?>" class="btn btn-danger">Voltar</a>
    </form>
    <div class="text_explicativo">
        Exemplo: No condomínio existem apartamentos 80, 202, 602 então a quantidade máxima de dígitos é 3.
    </div>
</div>