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
<div id="result_msg_apartamentos"></div>
<h3>Apartamentos cadastrados</h3><br/><br/>
<a href="<?= base_url('apartamentos/novo') ?>" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Novo Apartamento</a><br/><br/>
<?php if (!empty($apartamentos)) { ?>
    <div id="btns_top_table">
        <div id="div_btn_selecionar_todos">
            <button type="button" onclick="selecionar_todos('apartamentos_checkbox', 1, 'selecionar_todos_apartamentos', 'apartamentos_btn_delete_selecionados', 'desmarcar_todos_apartamentos')" class="btn btn-success" id="selecionar_todos_apartamentos">Selecionar todos</button>
            <button type="button" onclick="selecionar_todos('apartamentos_checkbox', 2, 'desmarcar_todos_apartamentos', 'apartamentos_btn_delete_selecionados', 'selecionar_todos_apartamentos')" class="btn btn-success" id="desmarcar_todos_apartamentos">Desmarcar todos</button>
        </div>
        <div id="div_apartamentos_btn_delete_selecionados">
            <button id="apartamentos_btn_delete_selecionados" type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal_delete_selecionados" type="button">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
                Excluir selecionados
            </button>
        </div>
    </div>
    <table class="table table-striped">
        <tr>
            <th class="table_title">Sel</th>
            <th class="table_title">Bloco</th>
            <th class="table_title">Número</th>
            <th class="table_title">Ações</th>
            <th class="table_title display_none_table">Cod Ativação</th>
        </tr>
        <?php
    }
    foreach ($apartamentos as $a) {
        if (!isset($a->nome)) {
            $bloco = "Sem bloco";
        } else {
            $bloco = $a->nome;
        }
        ?>
        <tr>
            <td><input type="checkbox" onclick="ativa_desativa_btn_delete_selecionados(this.className)" class="apartamentos_checkbox" name="apartamentos_checkbox" value="<?= $a->id ?>"></td>
            <td><?= $bloco ?></td>
            <td><?= $a->numero_apartamento ?></td>
            <td>
                <a type="button" href="<?= base_url('apartamentos/editar/' . $a->id) ?>" class="btn btn-warning">
                    <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span> 
                    <span class="display_none_table">Editar</span>
                </a>
                <button onclick="delete_apartamento(<?= $a->id ?>)" type="button" class="btn btn-danger">
                    <span class="glyphicon glyphicon-trash" aria-hidden="true"></span> 
                    <span class="display_none_table">Excluir</span>
                </button>
                <button class="btn btn-success" onclick="esvazia_apartamento(<?= $a->id ?>, <?= $a->bloco_id ?>)" data-toggle="modal" data-target="#modal_esvazia_apartamento" type="button"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> <span class="display_none_table">Desocupar apartamento</span></button>
            </td>
            <td class="display_none_table"><?= $a->cod_hash ?></td>
        </tr>
        <?php
    }
    ?>
</table>
<!-- Modal -->
<div class="modal fade" id="modal_esvazia_apartamento" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirmação</h4>
            </div>
            <div class="modal-body">
                Ao esvaziar o apartamento todos os usuários cadastrados bem como todos os telefones cadastrados para esse apartamento serão excluídos<br/><br/>
                <strong>Você tem certeza dessa ação?</strong><br/><br/>
                <form method="post" action="<?= base_url('apartamentos/esvazia_apartamento') ?>" name="form_esvazia_apartamento">
                    <input type="hidden" name="id_apartamento_esvaziar" id="id_apartamento_esvaziar">
                    <input type="hidden" name="id_bloco_esvaziar" id="id_bloco_esvaziar">
                </form>
                <button type="button" onclick="window.form_esvazia_apartamento.submit()" class="btn btn-success">Sim</button>
                <button type="button" data-dismiss="modal" class="btn btn-danger">Não</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_delete_selecionados" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Confirmação</h4>
            </div>
            <div class="modal-body">
                Você tem certeza que deseja deletar os apartamentos selecionados?<br/><br/>
                <button type="button" onclick="delete_apartamentos_selecionados()" class="btn btn-success">Sim</button>
                <button type="button" data-dismiss="modal" class="btn btn-danger">Não</button>
            </div>
        </div>
    </div>
</div>