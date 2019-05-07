<script>
    jQuery(function ($) {
        $("#cnpj").mask("99.999.999/9999-99");
        $("#cep").mask("99999-999");
        $("#telefone").mask("(99)99999999?9");
    });
</script>
<?php
if (empty($inputs)) {
    $inputs['nome'] = "";
    $inputs['cnpj'] = "";
    $inputs['rua'] = "";
    $inputs['numero'] = "";
    $inputs['cep'] = "";
    $inputs['estado'] = "";
    $inputs['cidade'] = "";
    $inputs['telefone'] = "";
    $inputs['email'] = "";
}
?>
<div class="painel_novo_user">
    <div id="form_login_top">
        <div id="form_login_top_esq"><span class="title_login_bold">Revenda </span><span class="title_login_light">Inteligente</span></div>
        <div id="form_login_top_dir"><img src="<?= base_url('assets/images/logo.jpg'); ?>"></div>
    </div>
    <?php
    if (isset($mensagens)) {
        ?>
        <div class="alert alert-danger msg_errors2" role="alert">
            <?php
            echo $mensagens;
            ?>
        </div>
        <?php
    }
    ?>
    <div class="text_explicativo"> 
        <div class="text_numeral">2</div> 
        Informe seus dados › Cadastro de revenda
    </div>
    <form id="form_novo_usuario" method="post" action="<?= base_url('usuarios/insert_perfil4') ?>">
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Nome da revenda</div>
                <input id="textNome" name="nome" value="<?= $inputs['nome'] ?>" class="form-control" placeholder=" Digite o nome da revenda" type="text">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">CNPJ</div>
                <input name="cnpj" id="cnpj" class="form-control" value="<?= $inputs['cnpj'] ?>" placeholder="Digite o CNPJ" type="text">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Rua</div>
                <input name="rua" class="form-control" value="<?= $inputs['rua'] ?>" placeholder="Digite a rua" type="text">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Número</div>
                <input name="numero" class="form-control" value="<?= $inputs['numero'] ?>" placeholder="Digite o número" type="text">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">CEP</div>
                <input name="cep" id="cep" class="form-control" value="<?= $inputs['cep'] ?>" placeholder="Digite o CEP" type="text">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Estado</div>
                <select name="estado" onchange="get_cidades(this, 0, 0)" class="form-control"type="text">
                    <option>Selecione um estado</option>
                    <option></option>
                    <?php
                    foreach ($estados as $e) {
                        if ($inputs['estado'] == $e->id) {
                            ?>
                            <option selected="selected" value="<?= $e->id ?>"><?= $e->nome ?></option>
                            <?php
                        } else {
                            ?>
                            <option value="<?= $e->id ?>"><?= $e->nome ?></option>
                            <?php
                        }
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Cidade</div>
                <select id="inputCidadeResult" name="cidade" disabled="true" class="form-control">
                    <option>Selecione uma cidade</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Telefone</div>
                <input name="telefone" id="telefone" class="form-control" value="<?= $inputs['telefone'] ?>" placeholder="Digite o telefone" type="text">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">E-mail</div>
                <input name="email" class="form-control" value="<?= $inputs['email'] ?>" placeholder="Digite o e-mail" type="email">
            </div>
        </div>
        <div class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Senha</div>
                <input name="senha" class="form-control" placeholder="Digite a senha" type="password">
            </div>
        </div>
        <div id="input_r_senha" class="form-group">
            <div class="input-group">
                <div class="input-group-addon">Repita a senha</div>
                <input name="r_senha" class="form-control" placeholder="Repita a senha" type="password">
            </div>
        </div>
        <div class="g-recaptcha" data-sitekey="6LdpTRATAAAAABBO_M6sU4s-ZnvG-oJQ7i61_463"></div>
        <script src='https://www.google.com/recaptcha/api.js?hl=pt-BR'></script><br/>
        <button type="submit" onclick="ativa_loading()" class="btn btn-success">Salvar</button>
        <a href="<?= base_url('usuarios/primeiro_acesso') ?>" class="btn btn-danger">Voltar</a>
    </form>
</div>
<script>
    get_cidades(<?= $inputs['estado'] ?>,<?= $inputs['cidade'] ?>, 1);
</script>