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
    <div class="alert alert-danger" role="alert">
        <?php echo $mensagens_erro ?>
    </div>
    <?php
}
?>
<div id="result_msg_equipamentos"></div>
<h3>Blocos cadastrados</h3><br/><br/>
<a href="<?= base_url('blocos/novo') ?>" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo bloco</a><br/><br/>
<table class="table table-striped">
    <?php if (count($blocos) > 0) { ?>
        <tr>
            <th class="table_title">Nome</th>
            <th class="table_title">Nº do bloco</th>
            <th class="table_title">Ações</th>
        </tr>
        <?php
    }
    foreach ($blocos as $b) {
        if ($b->identificador != 0) {
            ?>
            <tr>
                <td><?= $b->nome ?></td>
                <td><?= $b->identificador ?></td>
                <td>
                    <a type="button" href="<?= base_url('blocos/editar/' . $b->id) ?>" class="btn btn-warning">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 
                        <span class="display_none_table">Editar</span>
                    </a>
                    <button onclick="delete_bloco(<?= $b->id ?>)" type="button" class="btn btn-danger">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
                        <span class="display_none_table">Excluir</span>
                    </button>
                </td>
            </tr>
            <?php
        }
    }
    ?>
</table>