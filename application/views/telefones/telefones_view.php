<?php
$numero_apartamento = $nome_bloco . "apartamento " . $numero_apartamento[0]->numero_apartamento;
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

if (!isset($apartamento_selecionado)) {
    $apartamento_selecionado = 0;
}
?>
<div id="result_msg_telefone"></div>
<h3>Telefones cadastrados / <?= $numero_apartamento ?></h3><br/><br/>
<a href="<?= base_url('telefones/novo/') ?>" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo Telefone</a><br/><br/>
<div class="table-responsive">
    <table class="table table-striped">
        <?php if (!empty($telefones)) { ?>
            <tr>
                <th class="table_title display_none_table">Ordem</th>
                <th class="table_title">Telefone</th>
                <th class="table_title display_none_table">Nome</th>
                <th class="table_title">Status</th>
                <th class="table_title">Ações</th>
                <th class="table_title">Alterar ordem</th>
            </tr>
        <?php }
        ?>

        <?php
        foreach ($telefones as $t) {
            if ($t->memoria) {
                $memoria = "Sim";
            } else {
                $memoria = "Não";
            }
            ?>
            <tr>
                <td class="display_none_table"><?= $t->ordem ?></td>
                <td><?= $t->telefone ?></td>
                <td class="display_none_table"><?= $t->nome ?></td>
                <td>
                    <?php
                    foreach ($transmissao as $tr) {
                        if ($tr['telefone'] == $t->telefone) {
                            if ($tr['status']) {
                                ?>
                                <span class="font_green"><span class="glyphicon glyphicon-ok" aria-hidden="true"> </span><span class="display_none_table"> Enviado para o equipamento</span></span>
                                <?php
                            } else {
                                ?>
                                <span class="font_red"><span class="glyphicon glyphicon-alert" aria-hidden="true"> </span><span class="display_none_table"> Aguardando envio para o equipamento</span></span>
                                <?php
                            }
                        }
                    }
                    ?>
                </td>
                <td>
                    <a type="button" href="<?= base_url('telefones/editar/' . $t->id) ?>" class="btn btn-warning">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 
                        <span class="display_none_table">Editar</span>
                    </a>
                    <button onclick="delete_telefone(<?= $t->id ?>, <?= $apartamento_selecionado ?>)" type="button" class="btn btn-danger">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
                        <span class="display_none_table">Excluir</span>
                    </button>
                </td>
                <td>
                    <button type="button" onclick="altera_ordem(<?= $t->id ?>, 1, <?= $apartamento_selecionado ?>)" class="btn btn-success">
                        <span class="glyphicon glyphicon-menu-up"  aria-hidden="true"></span> 
                    </button>
                    <button type="button" onclick="altera_ordem(<?= $t->id ?>, 2, <?= $apartamento_selecionado ?>)" class="btn btn-success">
                        <span class="glyphicon glyphicon-menu-down" aria-hidden="true"></span> 
                    </button>
                </td>
            </tr>
            <?php
        }
        ?>
    </table>
</div>
<!-- Modal -->
<div class="modal fade" id="modal_ver_faixas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Faixas de horários</h4>
            </div>
            <div class="modal-body">
                <div id="loading_modal">

                </div>
                <table id="result_table_faixas" class="table table-striped table-faixas">
                </table>
            </div>
        </div>
    </div>
</div>
<div class="alert alert-info" role="alert">
    <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Os números adicionados receberão as chamadas do <?= $numero_apartamento ?> quando o mesmo for acionado/chamado
</div>