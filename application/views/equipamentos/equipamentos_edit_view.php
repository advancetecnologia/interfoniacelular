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
if ($equipamento[0]->equip_portaria) {
    $bloco_selecionado = '9db1737630d272e4fe7673185fa9db36';
} else {
    $bloco_selecionado = $equipamento[0]->id_bloco;
}
?>

<h3>Editar equipamento</h3>
<form class="formularios" method="post" action="<?= base_url('equipamentos/salva_edicao') ?>" data-toggle="validator" role="form">
    <input type="hidden" name="id_condominio" id="id_condominio" value="<?= $id_condominio ?>">
    <input type="hidden" name="id" value="<?= $equipamento[0]->id ?>">
    <div class="panel panel-default">
        <div class="panel-heading">Dados do equipamento</div>
        <div class="panel-body">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Nome</div>
                    <input id="textNome" name="nome" value="<?= $equipamento[0]->nome ?>" class="form-control" placeholder=" Digite o Nome" type="text">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">IMEI</div>
                    <input id="textNome" disabled="true" name="imei" value="<?= $equipamento[0]->imei ?>" class="form-control" placeholder=" Digite o IMEI" type="text">
                </div>
            </div>
            <div id="qtde_digitos_apartamento" class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Quant. dígitos apartamento</div>
                    <input id="textNome" name="qtd_digitos"  value="<?= $equipamento[0]->qtd_digitos ?>" class="form-control" placeholder=" Digite a quantidade dígitos" type="text">
                </div>
            </div>
            <input type="hidden" name="control_qtd_digitos" id="control_qtd_digitos" value="0">
            <?php
            if (!$this->session->userdata('bloco_unico')) {
                ?>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">Bloco</div>
                        <select onchange='qtde_digitos_apartamento(this)' name="id_bloco" id="id_bloco" class="form-control">
                            <option>Carregando...</option>
                        </select>
                        <span class="input-group-btn">
                            <button class="btn btn-success" onclick="limpa_campo_novo_bloco()" data-toggle="modal" data-target="#modal_cad_bloco" type="button"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span><span class="display_none_table"> Cadastrar bloco</span></button>
                        </span>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <input type="hidden" name="id_bloco" value="<?= $this->session->userdata('id_bloco_unico') ?>">
                <?php
            }
            ?>
        </div>
    </div>
    <button type="submit" id="btn_disabled" disabled="true" onclick="ativa_loading()" class="btn btn-success">Salvar equipamento</button>
</form>
<div class="modal fade" id="modal_cad_bloco" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <input type="hidden" id="bloco_selecionado" value="<?= $bloco_selecionado ?>">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Cadastro de bloco</h4>
            </div>
            <div id="cad_bloco_error" class="alert alert-danger" role="alert">
            </div>
            <div id="cad_bloco_success" class="alert alert-success" role="alert">
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">Nome do bloco</div>
                        <input id="nome_bloco" name="nome_bloco" class="form-control" placeholder=" Digite o nome bloco" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">Nº do bloco</div>
                        <input id="id_identificador" name="id_identificador" class="form-control" placeholder=" Digite o número do bloco (Somente números)" type="text">
                    </div>
                </div>
                <button type="button" onclick="salva_bloco(1)" class="btn btn-success">Salvar bloco</button>
            </div>
        </div>
    </div>
</div>
<?php
if (!$this->session->userdata('bloco_unico')) {
    ?>
    <script>
        get_blocos(1);
    </script>
    <?php
} else {
    ?>
    <script>
        ativa_btn_salvar();
    </script>
    <?php
}
?>
