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

<h3>Editar bloco</h3>
<form class="formularios" method="post" action="<?= base_url('blocos/salva_edicao') ?>" data-toggle="validator" role="form">
    <input type="hidden" name="id_condominio" id="id_condominio" value="<?= $id_condominio ?>">
    <input type="hidden" name="id" value="<?= $bloco[0]->id ?>">
    <div class="panel panel-default">
        <div class="panel-heading">Dados do bloco</div>
        <div class="panel-body">
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Nome do bloco</div>
                    <input id="nome_bloco" name="nome" value="<?= $bloco[0]->nome ?>" class="form-control" placeholder=" Digite o nome bloco" type="text">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <div class="input-group-addon">Nº do bloco</div>
                    <input id="id_identificador" value="<?= $bloco[0]->identificador ?>" name="identificador" class="form-control" placeholder=" Digite o número do bloco (Somente números)" type="text">
                </div>
            </div>
        </div>
    </div>
    <button type="submit" onclick="ativa_loading()" class="btn btn-success">Salvar bloco</button>
</form>