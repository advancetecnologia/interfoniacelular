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
<div id="result_msg_equipamentos"></div>
<h3>Equipamentos cadastrados</h3><br/><br/>
<a href="<?= base_url('equipamentos/novo') ?>" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo equipamento</a><br/><br/>
<div class="table-responsive">
    <table class="table table-striped">
        <?php if (!empty($equipamentos)) { ?>
            <tr>
                <th class="table_title">Nome</th>
                <th class="table_title display_none_table">Imei</th>
                <th class="table_title">Bloco</th>
                <th class="table_title display_none_table">Quantidade de dígitos</th>
                <th class="table_title">Ações</th>
            </tr>
            <?php
        }
        foreach ($equipamentos as $e) {
            if (empty($e->bloco)) {
                if ($e->equip_portaria) {
                    $bloco = "Todos os blocos";
                } else {
                    $bloco = "Sem bloco";
                }
            } else {
                $bloco = $e->bloco;
            }

            if ($e->qtd_digitos == 0) {
                $qtd_digitos = "Aceita qualquer quantidade";
            } else {
                $qtd_digitos = $e->qtd_digitos;
            }
            ?>
            <tr>
                <td><?= $e->nome ?></td>
                <td class="display_none_table"><?= $e->imei ?></td>
                <td><?= $bloco ?></td>
                <td class="display_none_table"><?= $qtd_digitos ?></td>
                <td>
                    <a type="button" href="<?= base_url('equipamentos/editar/' . $e->id) ?>" class="btn btn-warning">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 
                        <span class="display_none_table">Editar</span>
                    </a>
                    <button onclick="delete_equipamento(<?= $e->id ?>)" type="button" class="btn btn-danger">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
                        <span class="display_none_table">Excluir</span>
                    </button>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>