<script>
    jQuery(function ($) {
        $("#faixa-telefone-inicio").mask("99:99");
        $("#faixa-telefone-fim").mask("99:99");
    });
</script>
<?php
if (!empty($mensagens_sucesso)) {
    ?>
    <div class="alert alert-success" role="alert">
        <?php echo $mensagens_sucesso ?>
    </div>
    <?php
}
?>
<div id="faixas_horarios_telefone">
    <div class="text_faixa_horarios">Você deseja cadastrar as faixas de horários que esse senha terá permissão?
        <button type="button" onclick="inclui_faixas()" class="btn btn-success">Sim</button>
        <button type="button" onclick="window.location = '<?= base_url('senhas') ?>'" class="btn btn-danger">Não</button>
    </div>
    OBS: Se você não cadastrar nenhuma faixa de horário, essa senha ficará disponível todos os horários.
</div>
<div id="return_insert_faixa">

</div>
<div id="incluir_faixas">
    <div class="panel panel-default">
        <div class="panel-heading">Faixas de horários</div>
        <div class="panel-body">
            <div class="form-inline">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">Início</div>
                        <input id="faixa-telefone-inicio" class="form-control" value="00:00" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-addon">Fim</div>
                        <input id="faixa-telefone-fim" class="form-control" value="23:59" type="text">
                    </div>
                </div>
            </div>
            <div class="checkbox">
                <label>
                    <input name="dias_semana" value="1" id="seg" type="checkbox"> Segunda-feira
                </label>
                <label>
                    <input name="dias_semana" value="2" id="ter" type="checkbox"> Terça-feira
                </label>
                <label>
                    <input name="dias_semana" value="3" id="qua" type="checkbox"> Quarta-feira
                </label>
                <label>
                    <input name="dias_semana" value="4" id="qui" type="checkbox"> Quinta-feira
                </label>
                <label>
                    <input name="dias_semana" value="5" id="sex" type="checkbox"> Sexta-feira
                </label>
                <label>
                    <input name="dias_semana" value="6" id="sab" type="checkbox"> Sábado
                </label>
                <label>
                    <input name="dias_semana" value="7" id="dom" type="checkbox"> Domingo
                </label>
                <a href="#" id="btn_marca_todos" onclick="marcar_todos()">
                    <span class="glyphicon glyphicon-check" aria-hidden="true"></span> Marcar todos
                </a>
                <a onclick="salva_faixa_telefone(<?= $id_senha ?>)" class="btn btn-success btn-inline">OK</a>
            </div>
            <table id="result_table_faixas" class="table table-striped table-faixas">
            </table>
            <button type="button" onclick="window.location = '<?= base_url('senhas') ?>'" class="btn btn-success">Finalizar</button>
        </div>
    </div>
</div>
<script>
    get_faixas(<?= $id_senha ?>,1);
</script>
